<?php

namespace Bleuren\LaravelApi;

use Bleuren\LaravelApi\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function newQuery()
    {
        return new QueryBuilder($this->model->newQuery());
    }

    public function all()
    {
        return $this->newQuery()->get();
    }

    public function find($id, array $includes = [])
    {
        $query = $this->newQuery();
        if (! empty($includes)) {
            $query->applyIncludes($includes);
        }

        return $query->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);

        return $record;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
