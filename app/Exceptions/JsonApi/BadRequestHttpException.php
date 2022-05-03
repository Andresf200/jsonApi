<?php

namespace App\Exceptions\JsonApi;

use Exception;
use Illuminate\Http\JsonResponse;

class BadRequestHttpException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'errors' => [
                [
                    'title' => 'Bad Request',
                    'detail' => $this->getMessage(),
                    'status' => '400'
                ]
            ]
        ], 400);
    }
}
