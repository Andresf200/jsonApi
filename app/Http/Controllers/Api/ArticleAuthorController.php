<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
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
        return AuthorResource::make($article->author);
    }

    public function update(Article $article, Request $request)
    {
        $request->validate([
            'data.id' => 'exists:users,id'
        ]);

        $userId = $request->input('data.id');

        $user = User::where('id', $userId)->first();

        $article->update(['user_id' =>$user->id]);

        return AuthorResource::identifier($article->author);
    }
}
