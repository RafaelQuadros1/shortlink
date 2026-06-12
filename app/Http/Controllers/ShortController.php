<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortRequest;
use App\Http\Requests\UpdateShortRequest;
use App\Models\Click;
use App\Models\Short;
use App\Services\Decode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShortController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Short::class);

        $shorts = auth()->user()
            ->shorts()
            ->active()
            ->withCount('clicks')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('shorts.index', compact('shorts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Short::class);

        return view('shorts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShortRequest $request)
    {
        $short = Short::create(
            array_merge($request->validated(), [
                'user_id' => auth()->id(),
            ])
        );

        Log::channel('security')->info('Short link created', [
            'user_id' => auth()->id(),
            'short_id' => $short->id,
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'short_url' => $short->short_url,
            ], 201);
        }

        return redirect()->route('shorts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Short $shorts)
    {
        $this->authorize('delete', $shorts);

        Log::channel('security')->info('Short link deleted', [
            'user_id' => auth()->id(),
            'short_id' => $shorts->id,
            'ip' => request()->ip(),
        ]);

        $shorts->delete();

        return redirect()->route('shorts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Short $shorts)
    {
        $this->authorize('view', $shorts);

        if ($shorts->isExpired()) {
            return redirect()->route('shorts.not-found');
        }

        $shorts->loadCount('clicks');

        return view('shorts.show', ['short' => $shorts]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Short $shorts)
    {
        $this->authorize('update', $shorts);

        return view('shorts.edit', ['short' => $shorts]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShortRequest $request, Short $shorts)
    {
        $shorts->update($request->validated());

        Log::channel('security')->info('Short link updated', [
            'user_id' => auth()->id(),
            'short_id' => $shorts->id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('shorts.index');
    }

    public function redirect(string $code)
    {
        try {
            $id = (new Decode)->decode($code);
            $short = Short::findOrFail($id);
        } catch (ModelNotFoundException) {
            Log::warning('Short link not found', [
                'code' => $code,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('shorts.not-found');
        }

        if ($short->isExpired()) {
            Log::info('Short link expired', [
                'short_id' => $short->id,
                'code' => $code,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('shorts.not-found');
        }

        $ipHash = hash('sha256', request()->ip());
        $lockKey = "click_lock:{$short->id}:{$ipHash}";

        if (Cache::lock($lockKey, 5)->get()) {
            Click::create([
                'short_id' => $short->id,
                'ip_address' => $ipHash,
                'user_agent' => request()->userAgent(),
                'clicked_at' => now(),
            ]);
        }

        return redirect()->away($short->url_origin);
    }
}
