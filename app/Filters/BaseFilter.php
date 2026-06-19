<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter
{
    protected array $allowed = [];

    public function apply(Builder $query, array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, $key) && $this->isAllowed($key)) {
                $query = $this->{$key}($query, $value);
            }
        }

        return $query;
    }

    protected function isAllowed(string $key): bool
    {
        return empty($this->allowed) || in_array($key, $this->allowed, true);
    }
}
