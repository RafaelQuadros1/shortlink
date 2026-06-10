<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[Fillable('user_id', 'url_origin', 'short_code')]
#[Hidden('created_at', 'updated_at')]
class Short extends Model
{
    /** @use HasFactory<\Database\Factories\ShortFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
