<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ShortFilter extends BaseFilter
{
    protected array $allowed = ['search', 'sort', 'order'];

    protected function search(Builder $query, string $value): Builder
    {
        return $query->where('url_origin', 'like', "%{$value}%");
    }

    protected function sort(Builder $query, string $value): Builder
    {
        $order = request()->input('order', 'desc');
        $allowed = ['created_at', 'clicks'];
        $value = in_array($value, $allowed) ? $value : 'created_at';

        if ($value === 'clicks') {
            return $query->orderBy('clicks_count', $order);
        }

        return $query->orderBy($value, $order);
    }

    protected function order(Builder $query, string $value): Builder
    {
        return $query;
    }
}
