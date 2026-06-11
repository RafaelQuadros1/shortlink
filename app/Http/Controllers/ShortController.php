<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortRequest;
use App\Models\Short;
use App\Services\Decode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

    public function redirect(string $code)
    {
        $id = (new Decode)->decode($code);
        $short = Short::findOrFail($id);

        return redirect()->away($short->url_origin);
    }
}
