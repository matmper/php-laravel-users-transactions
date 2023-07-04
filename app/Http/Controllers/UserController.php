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
     *  security = {"jwt"},
     *  @OA\Response(response="200", description="Success")
     * )
     */
    public function me(): JsonResponse
    {
        $user = auth()->guard(config('auth.defaults.guard'))->user();
        $balance = $this->walletService->getBalance($user->id);

        return ResponseResource::handle($user, ['balance' => $balance], Response::HTTP_OK);
    }
}
