<?php

namespace Tests\Feature\Authors;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListAuthorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_author()
    {
        $author = User::factory()->create();

        $response = $this->getJson(route('api.v1.authors.show', $author));

        $response->assertJsonApiResource($author, [
            'name' => $author->name
        ]);

        $this->assertTrue(
            Str::isUuid($response->json('data.id')),
            "The author id must be UUID."
        );
    }

    /** @test */
    public function can_fetch_all_categories()
    {
        $this->withoutExceptionHandling();
        $authors = User::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.authors.index'));

        $response->assertJsonApiResourceCollection($authors, [
            'name',
        ]);
    }
}
