<?php

namespace Tests\Unit\JsonApi;

use App\JsonApi\Document;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    /** @test */
    public function can__create_json_documents()
    {
        $document = Document::type('articles')
            ->id('article-id')
            ->attributes([
                'title' => 'Article Title',
            ])->toArray();

        $expected = [
            'data' => [
            'type' => 'articles',
                'id' => 'article-id',
                'attributes' => [
                    'title' => 'Article Title',
                ]
            ]
        ];

        $this->assertEquals($expected,$document);
    }
}
