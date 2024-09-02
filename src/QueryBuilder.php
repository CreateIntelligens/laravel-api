<?php

namespace Bleuren\LaravelApi;

use Bleuren\LaravelApi\Contracts\QueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;

class QueryBuilder implements QueryBuilderInterface
{
    protected $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function applyFilters(array $filters)
    {
        foreach ($filters as $field => $value) {
            $this->query->where($field, $value);
        }

        return $this;
    }

    public function applySorts(array $sorts)
    {
        foreach ($sorts as $field => $direction) {
            $this->query->orderBy($field, $direction);
        }

        return $this;
    }

    public function applyIncludes(array $includes)
    {
        $this->query->with($includes);

        return $this;
    }

    public function paginate(?int $perPage = null)
    {
        return $this->query->paginate($perPage);
    }

    public function get()
    {
        return $this->query->get();
    }
}
