<?php

namespace Bleuren\LaravelApi;

use Bleuren\LaravelApi\Contracts\RepositoryInterface;
use Bleuren\LaravelApi\Contracts\ServiceInterface;
use Illuminate\Http\JsonResponse;

abstract class BaseService implements ServiceInterface
{
    protected $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): JsonResponse
    {
        $data = $this->repository->all();

        return response()->json($data);
    }

    public function find($id): JsonResponse
    {
        $data = $this->repository->find($id);

        return response()->json($data);
    }

    public function create(array $data): JsonResponse
    {
        $result = $this->repository->create($data);

        return response()->json($result, 201);
    }

    public function update($id, array $data): JsonResponse
    {
        $result = $this->repository->update($id, $data);

        return response()->json($result);
    }

    public function delete($id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }
}
