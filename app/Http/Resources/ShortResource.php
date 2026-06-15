<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property-read string $encrypted_id */
/** @property-read string $short_url */
class ShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->encrypted_id,
            'url_origin' => $this->url_origin,
            'short_code' => $this->short_code,
            'short_url' => $this->short_url,
            'expires_at' => $this->expires_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
