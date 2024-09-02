<?php

namespace Bleuren\LaravelApi\Contracts;

interface QueryBuilderInterface
{
    public function applyFilters(array $filters);

    public function applySorts(array $sorts);

    public function applyIncludes(array $includes);

    public function paginate(?int $perPage = null);

    public function get();
}
