<?php

namespace App\Models;

use App\Services\EncryptId;
use Database\Factories\ShortFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('user_id', 'url_origin', 'short_code')]
class Short extends Model
{
    /** @use HasFactory<ShortFactory> */
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

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
