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
        foreach ($filters as $field => $conditions) {
            $this->applyFilter($field, $conditions);
        }

        return $this;
    }

    protected function applyFilter($field, $conditions)
    {
        if (! is_array($conditions)) {
            $this->query->where($field, $conditions);

            return;
        }

        foreach ($conditions as $operator => $value) {
            switch ($operator) {
                case '$eq':
                    $this->query->where($field, $value);
                    break;
                case '$ne':
                    $this->query->where($field, '!=', $value);
                    break;
                case '$gt':
                    $this->query->where($field, '>', $value);
                    break;
                case '$lt':
                    $this->query->where($field, '<', $value);
                    break;
                case '$gte':
                    $this->query->where($field, '>=', $value);
                    break;
                case '$lte':
                    $this->query->where($field, '<=', $value);
                    break;
                case '$contains':
                    $this->query->where($field, 'like', "%$value%");
                    break;
                case '$not_contains':
                    $this->query->where($field, 'not like', "%$value%");
                    break;
                case '$in':
                    $this->query->whereIn($field, $value);
                    break;
                case '$not_in':
                    $this->query->whereNotIn($field, $value);
                    break;
            }
        }
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

    public function find($id)
    {
        return $this->query->findOrFail($id);
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
