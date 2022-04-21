<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse as JsonResponseAlias;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'articles',
            'id' => (string)$this->resource->getRouteKey(),
            'attributes' => [
                'title' => $this->resource->title,
                'slug' => $this->resource->slug,
                'content' => $this->resource->content
            ],
            'links' => [
                'self' => url('/api/v1/articles/' . $this->resource->getRouteKey())
            ]
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'location' => route('api.v1.articles.show',$this->resource)
        ]);
    }
}
