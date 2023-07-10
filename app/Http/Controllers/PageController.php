<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * @OA\Post(
     *  path="/",
     *  summary="Home page or index",
     *  tags={"Pages"},
     *  @OA\Response(response="201", description="created", @OA\JsonContent(example={
     *      "data": {
     *          "name": "Users Transactions"
     *      },
     *      "meta": {
     *          "version": "1"
     *      }
     *  })),
     * )
     */
    public function index(): JsonResponse
    {
        return ResponseResource::handle(
            ['name' => config('app.name')],
            ['version' => 1],
            Response::HTTP_OK,
        );
    }
}
