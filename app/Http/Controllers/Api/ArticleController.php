<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\SaveArticleRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $articles = Article::query()
            ->with('category')
            ->allowedFilters(['title', 'content', 'month', 'year'])
            ->allowedSorts(['title', 'content'])
            ->sparseFieldset()
            ->jsonPaginate();

        return ArticleResource::collection($articles);

    }

    public function show($article): JsonResource
    {
        $article = Article::where('slug', $article)
            ->with('category')
            ->sparseFieldset()
            ->firstOrFail();
        return ArticleResource::make($article);
    }

    public function store(SaveArticleRequest $request)
    {
        $article = Article::create($request->validated());
        return ArticleResource::make($article);
    }

    public function update(Article $article, SaveArticleRequest $request)
    {
        $article->update($request->validated());
        return ArticleResource::make($article);
    }

    public function destroy(Article $article): Response
    {
        $article->delete();
        return response()->noContent();
    }
}
