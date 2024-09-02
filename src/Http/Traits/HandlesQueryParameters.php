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
        return $request->input('filter', []);
    }

    protected function getSorts(Request $request)
    {
        $sorts = [];
        if ($request->has('sort')) {
            $sortParams = explode(',', $request->input('sort'));
            foreach ($sortParams as $param) {
                $direction = 'asc';
                if (strpos($param, '-') === 0) {
                    $direction = 'desc';
                    $param = substr($param, 1);
                }
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

        return explode(',', $includeString);
    }
}
