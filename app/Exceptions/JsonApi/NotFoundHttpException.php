<?php

namespace App\Exceptions\JsonApi;

use Exception;
use Illuminate\Http\JsonResponse;

class NotFoundHttpException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        $id = request()->input('data.id');
        $type = request()->input('data.type');
        return response()->json([
            'errors' => [
                [
                    'title' => 'Not Found',
                    'detail' => "No records found with the id '{$id}' in the '{$type}' resource.",
                    'status' => '404'
                ]
            ]
        ], 404);
    }
}
