<?php

namespace App\Http\Controllers;

use App\Actions\CreateShortAction;
use App\Actions\RedirectShortAction;
use App\Http\Requests\StoreShortRequest;
use App\Http\Requests\UpdateShortRequest;
use App\Models\Short;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShortController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Short::class);

        $perPage = min((int) request()->input('per_page', 10), 10);

        $shorts = auth()->user()
            ->shorts()
            ->active()
            ->withCount('clicks')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('shorts.index', compact('shorts'));
    }

    public function create()
    {
        $this->authorize('create', Short::class);

        return view('shorts.create');
    }

    public function store(StoreShortRequest $request, CreateShortAction $action)
    {
        $short = $action->execute($request, $request->validated());

        if ($request->expectsJson() || ! auth()->check()) {
            return response()->json([
                'short_url' => $short->short_url,
                'expires_at' => $short->expires_at?->toISOString(),
            ], 201);
        }

        return redirect()->route('shorts.index');
    }

    public function destroy(Short $shorts)
    {
        $this->authorize('delete', $shorts);

        $shorts->delete();

        return redirect()->route('shorts.index');
    }

    public function show(Short $shorts)
    {
        $this->authorize('view', $shorts);

        if ($shorts->isExpired()) {
            return redirect()->route('shorts.not-found');
        }

        $shorts->loadCount('clicks');

        return view('shorts.show', ['short' => $shorts]);
    }

    public function edit(Short $shorts)
    {
        $this->authorize('update', $shorts);

        return view('shorts.edit', ['short' => $shorts]);
    }

    public function update(UpdateShortRequest $request, Short $shorts)
    {
        $this->authorize('update', $shorts);

        $shorts->update($request->validated());

        return redirect()->route('shorts.index');
    }

    public function redirect(string $code, RedirectShortAction $action)
    {
        return $action->execute($code);
    }
}
