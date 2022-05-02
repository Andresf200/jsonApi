<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;

class ArticleAuthorController extends Controller
{
    public function index(Article $article)
    {
        return AuthorResource::identifier($article->author);
    }

    public function show(Article $article)
    {
        return AuthorResource::make($article->category);
    }
}
