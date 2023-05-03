<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter implements FilterInterface
{
    private array $queryParams = [];

    /**
     * @param array $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return array
     */
    abstract protected function getCallback(): array;

    /**
     * @param Builder $builder
     * @return mixed
     */
    public function apply(Builder $builder): mixed
    {
        $this->before($builder);

        foreach ($this->getCallback() as $name => $callback) {
            if (isset($this->queryParams[$name])) {
                call_user_func($callback, $builder, $this->queryParams[$name]);
            }
        }
    }

    /**
     * @param Builder $builder
     * @return void
     */
    protected function before(Builder $builder)
    {

    }

}
