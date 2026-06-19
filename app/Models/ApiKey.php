<?php

namespace App\Models;

use Database\Factories\ApiKeyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'name', 'key', 'key_lookup', 'abilities', 'expires_at', 'last_used_at'])]
#[Hidden(['key', 'id'])]
class ApiKey extends Model
{
    /** @use HasFactory<ApiKeyFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'abilities' => 'array',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKey(): array
    {
        $plain = 'sk_'.Str::random(40);

        return [
            'plain' => $plain,
            'hashed' => Hash::make($plain),
            'key_lookup' => hash('sha256', $plain),
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function hasAbility(string $ability): bool
    {
        return $this->abilities === null || in_array($ability, $this->abilities, true);
    }
}
