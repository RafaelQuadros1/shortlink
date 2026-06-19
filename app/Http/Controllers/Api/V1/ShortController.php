<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\ShortFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreShortApiRequest;
use App\Http\Resources\ShortResource;
use App\Models\Short;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortController extends Controller
{
    use AuthorizesRequests;

    private ShortFilter $shortFilter;

    public function __construct()
    {
        $this->shortFilter = new ShortFilter;
    }

    public function index()
    {
        $perPage = max(1, min((int) request()->input('per_page', 10), 10));

        $query = Short::query()
            ->where('user_id', auth()->id())
            ->active()
            ->withCount('clicks');

        $filters = request()->only(['search', 'sort', 'order']);

        $shorts = $this->shortFilter->apply($query, $filters)->latest()->paginate($perPage);

        return ShortResource::collection($shorts);
    }

    public function store(StoreShortApiRequest $request)
    {
        $short = Short::create(
            array_merge($request->validated(), [
                'user_id' => auth()->id(),
            ])
        );

        activity()
            ->performedOn($short)
            ->event('created')
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url_origin' => $short->url_origin,
            ])
            ->log('Short link created via API');

        return new ShortResource($short);
    }

    public function destroy(Request $request, Short $short): JsonResponse
    {
        abort_unless($short->user_id === auth()->id(), 404);

        activity()
            ->performedOn($short)
            ->event('deleted')
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url_origin' => $short->url_origin,
            ])
            ->log('Short link deleted via API');

        $short->delete();

        return response()->json(null, 204);
    }
}
