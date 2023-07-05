<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(protected WalletService $walletService)
    {
        //
    }

    /**
     * @OA\Get(
     *  path="/me",
     *  summary="Info about authenticated user",
     *  tags={"Users"},
     *  security = {{"bearer":{}}},
     *  @OA\Response(response="200", description="success", @OA\JsonContent(example={
     *      "data":{
     *          "id": 1,
     *          "public_id": "4b0a8cf3-dbb0-43a1-9b99-a90056793ade",
     *          "name": "User Name",
     *          "email": "user@mail.com",
     *          "document_number": "12345678900",
     *          "type": "pf",
     *          "created_at": "2023-07-04T05:58:15.000000Z",
     *          "updated_at": "2023-07-04T05:58:15.000000Z",
     *          "deleted_at": null
     *      },
     *      "meta":{"balance":1000}
     *  })),
     *  @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *  )),
     * )
     */
    public function me(): JsonResponse
    {
        $user = auth()->guard(config('auth.defaults.guard'))->user();
        $balance = $this->walletService->getBalance($user->id);

        return ResponseResource::handle($user, ['balance' => $balance], Response::HTTP_OK);
    }
}
