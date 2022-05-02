<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorController extends Controller
{
    public function show($autor): JsonResource
    {
        $author = User::findOrFail($autor);

        return AuthorResource::make($author);
    }

    public function index()
    {
        $authors = User::jsonPaginate();

        return AuthorResource::collection($authors);
    }
}
