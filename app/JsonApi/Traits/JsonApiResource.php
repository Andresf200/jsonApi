<?php

namespace App\JsonApi\Traits;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait JsonApiResource
{
    abstract public function toJsonApi():array; //sirve para obligar a aque se cree esta funcion los que utilizen el trait

    public function toArray($request)
    {
        return [
            'type' => $this->getResourceType(),
            'id' => (string)$this->resource->getRouteKey(),
            'attributes' => $this->filterAttributes($this->toJsonApi()),
            'links' => [
                'self' => route('api.v1.'.$this->getResourceType().'.show', $this->resource)
            ]
        ];
    }

    public function withResponse($request,$response)
    {
        $response->header(
            'location',
            route('api.v1.'.$this->getResourceType().'.show',$this->resource)
        );
    }

    public function filterAttributes($attributes): array
    {
        return array_filter($attributes, function($value) {
            if (request()->isNotFilled('fields')) {
                return true;
            }

            $fields = explode(',', request('fields.'.$this->getResourceType()));

            if ($value === $this->getRouteKey()) {
                return in_array($this->getRouteKeyName(), $fields);
            }

            return $value;
        });
    }

    public static function collection($resource): AnonymousResourceCollection
    {
        $collection = parent::collection($resource);

        $collection->with['links'] = ['self' => $resource->path()];
        return $collection;
    }

}
