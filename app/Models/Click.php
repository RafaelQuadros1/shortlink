<?php

namespace App\Models;

use Database\Factories\ClickFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('short_id', 'ip_address', 'user_agent', 'referrer', 'clicked_at')]
class Click extends Model
{
    /** @use HasFactory<ClickFactory> */
    use HasFactory;

    public function short(): BelongsTo
    {
        return $this->belongsTo(Short::class);
    }
}
