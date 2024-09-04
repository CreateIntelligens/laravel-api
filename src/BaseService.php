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

    public function find($id, array $params = []): JsonResponse
    {
        $includes = $params['includes'] ?? [];
        $data = $this->repository->find($id, $includes);

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

    public function getFiltered(array $params): JsonResponse
    {
        $query = $this->repository->newQuery();

        if (isset($params['filters'])) {
            $query->applyFilters($params['filters']);
        }

        if (isset($params['sorts'])) {
            $query->applySorts($params['sorts']);
        }

        if (isset($params['includes'])) {
            $query->applyIncludes($params['includes']);
        }

        if (isset($params['page'])) {
            $perPage = $params['per_page'] ?? 15;
            $result = $query->paginate($perPage);
        } else {
            $result = $query->get();
        }

        return response()->json($result);
    }
}
