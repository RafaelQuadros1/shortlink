<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortRequest;
use App\Http\Requests\UpdateShortRequest;
use App\Jobs\RecordClickJob;
use App\Models\Short;
use App\Services\Decode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

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
        $this->authorize('create', Short::class);

        $short = Short::create(
            array_merge($request->validated(), [
                'user_id' => auth()->id(),
            ])
        );

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

        $shorts->delete();

        return redirect()->route('shorts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Short $shorts)
    {
        $this->authorize('view', $shorts);

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
        $this->authorize('update', $shorts);

        $shorts->update($request->validated());

        return redirect()->route('shorts.index');
    }

    public function redirect(string $code)
    {
        try {
            $id = (new Decode)->decode($code);
        } catch (\Exception) {
            return redirect()->route('shorts.not-found');
        }

        $url = Cache::get("short:{$id}");

        if ($url === null) {
            $short = Short::find($id);

            if (! $short) {
                return redirect()->route('shorts.not-found');
            }

            $url = $short->url_origin;
            Cache::put("short:{$id}", $url, 3600);
        }

        $ipHash = hash('sha256', request()->ip() ?? request()->userAgent() ?? 'anonymous');
        $lockKey = "click_lock:{$id}:{$ipHash}";

        if (Cache::lock($lockKey, 5)->get()) {
            RecordClickJob::dispatch(
                shortId: $id,
                ipAddress: $ipHash,
                userAgent: request()->userAgent(),
            );
        }

        return redirect()->away($url);
    }
}
