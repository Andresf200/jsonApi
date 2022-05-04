<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_update_articles()
    {
        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article))
            ->assertUnauthorized();

//        $response->assertJsonApiError();
    }

    /** @test */
    public function can_update_articles()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update article',
            'slug' => $article->slug,
            'content' => 'Update Content'
        ])->assertOk();

        $response->assertJsonApiResource($article, [
            'title' => 'Update article',
            'slug' => $article->slug,
            'content' => 'Update Content'
        ]);
    }

    /** @test */
    public function title_is_required()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'slug' => 'update-article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nue',
            'slug' => 'update-article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function slug_is_required()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Updated Article',
            'content' => 'Article content'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();
        Sanctum::actingAs($article1->author);
        $this->patchJson(route('api.v1.articles.update', $article1), [
            'title' => 'Nuevo Articulo',
            'slug' => $article2->slug,
            'content' => 'Contenido del artículo'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo Articulo',
            'slug' => '%$!°!#%&/$%',
            'content' => 'Contenido del artículo'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_contain_underscores()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo Articulo',
            'slug' => 'with___underscores',
            'content' => 'Contenido del artículo'
        ])->assertSee(trans('validation.no_underscores', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_start_with_dashes()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo Articulo',
            'slug' => '-starts-with-dashes',
            'content' => 'Contenido del artículo'
        ])->assertSee(trans('validation.no_starting_dashes', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_end_with_dashes()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo Articulo',
            'slug' => 'end-with-dashes-',
            'content' => 'Contenido del artículo'
        ])->assertSee(trans('validation.no_ending_dashes', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Updated Article',
            'slug' => 'update-article'
        ])->assertJsonApiValidationErrors('content');
    }
}
