<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    /**
     * @param array|object $data
     * @param array $meta
     * @param integer $statusCode
     * @return JsonResponse
     */
    public static function handle(array|object $data, array $meta, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'meta' => $meta,
        ], $statusCode);
    }
}
