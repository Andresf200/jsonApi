<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('articles', ArticleController::class);

Route::apiResource('categories', CategoryController::class)
    ->only('index', 'show');


