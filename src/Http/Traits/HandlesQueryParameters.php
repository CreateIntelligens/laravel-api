<?php

namespace Bleuren\LaravelApi\Http\Traits;

use Illuminate\Http\Request;

trait HandlesQueryParameters
{
    protected function getQueryParameters(Request $request)
    {
        return [
            'filters' => $this->getFilters($request),
            'sorts' => $this->getSorts($request),
            'includes' => $this->getIncludes($request),
            'page' => $request->input('page'),
            'per_page' => $request->input('per_page'),
        ];
    }

    protected function getFilters(Request $request)
    {
        $filters = $request->input('filter', []);

        return $this->parseFilters($filters);
    }

    protected function parseFilters($filters)
    {
        $parsedFilters = [];
        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $parsedFilters[$field] = $this->parseFilterOperators($value);
            } else {
                $parsedFilters[$field] = $value;
            }
        }

        return $parsedFilters;
    }

    protected function parseFilterOperators($conditions)
    {
        $validOperators = ['$eq', '$ne', '$gt', '$lt', '$gte', '$lte', '$contains', '$not_contains', '$in', '$not_in'];
        $parsedConditions = [];
        foreach ($conditions as $operator => $value) {
            if (in_array($operator, $validOperators)) {
                $parsedConditions[$operator] = $value;
            }
        }

        return $parsedConditions;
    }

    protected function getSorts(Request $request)
    {
        $sortString = $request->input('sort');
        if (empty($sortString)) {
            return [];
        }
        $sorts = [];
        $sortParams = explode(',', $sortString);
        foreach ($sortParams as $param) {
            $param = trim($param);
            if (empty($param)) {
                continue;
            }
            $direction = 'asc';
            if (strpos($param, '-') === 0) {
                $direction = 'desc';
                $param = substr($param, 1);
            }
            if (! empty($param)) {
                $sorts[$param] = $direction;
            }
        }

        return $sorts;
    }

    protected function getIncludes(Request $request)
    {
        $includeString = $request->input('include');
        if (empty($includeString)) {
            return [];
        }

        return array_filter(explode(',', $includeString), 'strlen');
    }
}
