<?php

namespace Tests\Unit\JsonApi;



use App\Models\Category;
use App\JsonApi\Document;
use Mockery;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    /** @test */
    public function can__create_json_documents()
    {
        $category = Mockery::mock('Category',function($mock){
            $mock->shouldReceive('getResourceType')->andReturn('categories');
            $mock->shouldReceive('getRouteKey')->andReturn('category-id');
        });
        $document = Document::type('articles')
            ->id('article-id')
            ->attributes([
                'title' => 'Article Title',
            ])->relationshipData([
              'category' => $category
            ])->toArray();

        $expected = [
            'data' => [
            'type' => 'articles',
                'id' => 'article-id',
                'attributes' => [
                    'title' => 'Article Title',
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => 'category-id'
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected,$document);
    }
}
