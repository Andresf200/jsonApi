<?php

use App\Http\Controllers\Api\ArticleController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('articles', ArticleController::class)
    ->names('api.v1.articles');
