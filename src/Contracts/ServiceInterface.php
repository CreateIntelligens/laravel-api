<?php

namespace Bleuren\LaravelApi\Contracts;

use Illuminate\Http\JsonResponse;

interface ServiceInterface
{
    public function all(): JsonResponse;

    public function find($id): JsonResponse;

    public function create(array $data): JsonResponse;

    public function update($id, array $data): JsonResponse;

    public function delete($id): JsonResponse;
}
