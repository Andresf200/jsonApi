<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::jsonPaginate();

        return CategoryResource::collection($categories);
    }
    public function show($category)
    {
        $category = Category::where('slug',$category)->firstOrFail();

        return CategoryResource::make($category);
    }
}
