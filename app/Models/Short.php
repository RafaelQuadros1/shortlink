<?php

namespace App\Models;

use App\Services\EncryptId;
use Database\Factories\ShortFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

#[Fillable('user_id', 'url_origin', 'short_code', 'expires_at')]
class Short extends Model
{
    /** @use HasFactory<ShortFactory> */
    use HasFactory, LogsActivity;

    protected $hidden = ['created_at', 'updated_at'];

    protected static $logAttributes = ['url_origin', 'short_code', 'expires_at'];

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        try {
            $id = (new EncryptId)->decrypt($value);
        } catch (DecryptException) {
            return null;
        }

        return $this->where($this->getRouteKeyName(), $id)->first();
    }

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('shorts.redirect', $this->short_code),
        );
    }

    protected function encryptedId(): Attribute
    {
        return Attribute::make(
            get: fn () => (new EncryptId)->encrypt($this->id),
        );
    }
}
