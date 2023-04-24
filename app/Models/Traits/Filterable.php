<?php

namespace App\Models\Traits;

use App\Http\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param Builder $bulder
     * @param FilterInterface $filter
     * @return Builder
     */
    public function scopeFilter(Builder $bulder, FilterInterface $filter)
    {
        $filter->apply($bulder);

        return $bulder;
    }
}
