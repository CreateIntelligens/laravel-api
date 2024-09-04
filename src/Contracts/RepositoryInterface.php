<?php

namespace Bleuren\LaravelApi\Contracts;

interface RepositoryInterface
{
    public function all();

    public function find($id, array $includes = []);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function newQuery();
}
