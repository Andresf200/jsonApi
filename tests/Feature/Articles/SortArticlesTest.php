<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_sort_articles_by_title()
    {
        Article::factory()->create(['title' => 'C title']);
        Article::factory()->create(['title' => 'B title']);
        Article::factory()->create(['title' => 'A title']);

        // /articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => 'title']);

        $this->getJson($url)->assertSeeInOrder([
            'A title',
            'B title',
            'C title',
        ]);
    }

    /** @test */
    public function can_sort_articles_by_title_desc()
    {
        Article::factory()->create(['title' => 'C title']);
        Article::factory()->create(['title' => 'A title']);
        Article::factory()->create(['title' => 'B title']);

        // /articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => '-title']);

        $this->getJson($url)->assertSeeInOrder([
            'C title',
            'B title',
            'A title',
        ]);
    }

    /** @test */
    public function can_sort_articles_by_content()
    {
        Article::factory()->create(['content' => 'C content']);
        Article::factory()->create(['content' => 'B content']);
        Article::factory()->create(['content' => 'A content']);

        // /articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => 'content']);

        $this->getJson($url)->assertSeeInOrder([
            'A content',
            'B content',
            'C content',
        ]);
    }

    /** @test */
    public function can_sort_articles_by_content_desc()
    {
        Article::factory()->create(['content' => 'C content']);
        Article::factory()->create(['content' => 'A content']);
        Article::factory()->create(['content' => 'B content']);

        // /articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => '-content']);

        $this->getJson($url)->assertSeeInOrder([
            'C content',
            'B content',
            'A content',
        ]);
    }

    /** @test */
    public function can_sort_articles_by_title_and_content()
    {
        Article::factory()->create([
            'title' => 'A title',
            'content' => 'A content'
        ]);

        Article::factory()->create([
            'title' => 'B title',
            'content' => 'B content'
        ]);
        Article::factory()->create([
            'title' => 'A title',
            'content' => 'C content'
        ]);

        // /articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => 'title,-content']);

        $this->getJson($url)->assertSeeInOrder([
            'C content',
            'A content',
            'B content',
        ]);
    }

    /** @test */
    public function cannot_sort_articles_by_unknown_fields()
    {
        Article::factory()->count(3)->create();
        // /articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => 'unknown']);

        $this->getJson($url)->assertStatus(400);
    }

}