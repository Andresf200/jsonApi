<?php

namespace App\JsonApi;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class JsonApiQueryBuilder
{
    public function allowedSorts(): \Closure
    {
        return function ($allowedStorts) {
            /** @var Builder $this**/
            if (request()->filled('sort')) {
                $sortFields = explode(',', request()->input('sort'));

                foreach ($sortFields as $sortField) {
                    $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';

                    $sortField = ltrim($sortField, '-');

                    abort_unless(in_array($sortField, $allowedStorts), 400);
                    $this->orderBy($sortField, $sortDirection);
                }
            }

            return $this;
        };
    }

    public function jsonPaginate(): \Closure
    {
       return function(){
            return $this->paginate(
            /** @var Builder $this**/
                $perpage = request('page.size',15),
                $columns = ['*'],
                $pagesName = 'page[number]',
                $page = request('page.number',1)
            )->appends(request()->only('sort','page.size'));
        };
    }
}
