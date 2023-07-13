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
        if ($statusCode >= 400) {
            throw new \Exception("Error Processing Request", 403);
        }

        return response()->json([
            'data' => $data,
            'meta' => $meta,
        ], $statusCode);
    }

    /**
     * @OA\Schema(
     *  schema="UnauthorizedResponse",
     *  @OA\Property(property="errors", type="array", example={Unauthorized [Authenticate]}),
     *  @OA\Property(property="meta", type="array", @OA\Items(
     *      @OA\Property(
     *         property="code",
     *         type="integer",
     *         example="401"
     *      ),
     *  )),
     * )
     */
    public static function error(array $errors, array $meta, int $statusCode): JsonResponse
    {
        return response()->json([
            'errors' => $errors,
            'meta' => $meta,
        ], $statusCode);
    }
}
