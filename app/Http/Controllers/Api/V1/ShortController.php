<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreShortApiRequest;
use App\Http\Resources\ShortResource;
use App\Models\Short;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class ShortController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $perPage = min((int) request()->input('per_page', 10), 10);

        $shorts = auth()->user()
            ->shorts()
            ->active()
            ->withCount('clicks')
            ->latest()
            ->paginate($perPage);

        return ShortResource::collection($shorts);
    }

    public function store(StoreShortApiRequest $request)
    {
        $short = Short::create(
            array_merge($request->validated(), [
                'user_id' => auth()->id(),
            ])
        );

        Log::channel('security')->info('Short link created via API', [
            'user_id' => auth()->id(),
            'short_id' => $short->id,
            'ip' => $request->ip(),
        ]);

        return new ShortResource($short);
    }
}
