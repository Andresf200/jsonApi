<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request): ArticleCollection
    {
        $articles = Article::query();

        if($request->filled('sort')){
            $sortFields = explode(',',$request->input('sort'));

            $allowedStorts = ['title','content'];

            foreach ($sortFields as $sortField){
                $sortDirection = Str::of($sortField)->startsWith('-')? 'desc' : 'asc';

                $sortField = ltrim($sortField,'-');

                abort_unless(in_array($sortField, $allowedStorts),400);
                $articles->orderBy($sortField,$sortDirection);
            }
        }

        return ArticleCollection::make($articles->get());
    }

    public function show(Article $article): ArticleResource
    {
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