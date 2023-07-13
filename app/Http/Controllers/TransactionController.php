<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Resources\ResponseResource;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService)
    {
        //
    }

    /**
     * @OA\Post(
     *  path="/transactions",
     *  summary="Create new transaction [user_pf]",
     *  tags={"Transaction"},
     *  security = {{"bearer":{}}},
     *  @OA\RequestBody(
     *     @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/TransactionStoreRequest")),
     *  ),
     *  @OA\Response(response="200", description="created", @OA\JsonContent(example={
     *      "data": {
     *          "public_id": "7aab891a-48b1-4f9f-b391-9c8567ef5eda",
     *          "amount": 1,
     *          "payer": "072f7f8c-e50e-4d03-9a32-3f7e33a28c76",
     *          "payee": "610be6d0-0759-4beb-8e7a-9d27f288c1c0"
     *      },
     *      "meta": {
     *          "success": true,
     *          "response": {
     *              "message": "Success"
     *          }
     *      }
     *  })),
     *  @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *  )),
     *  @OA\Response(response=500, description="error to create transaction")
     * )
     */
    public function store(TransactionStoreRequest $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->handler($request->payeeId, $request->amount)
                ->transaction()
                ->message()
                ->toArray();

            if (empty($transaction)) {
                throw new \Exception('error to create transaction', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return ResponseResource::handle($transaction['transaction'], $transaction['message'], Response::HTTP_CREATED);
    }
}
