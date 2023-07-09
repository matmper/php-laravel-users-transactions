<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreRequest;
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
     *  summary="Create new transaction",
     *  tags={"Transactions"},
     *  security = {{"bearer":{}}},
     *  @OA\RequestBody(
     *     @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/TransactionStoreRequest")),
     *  ),
     *  @OA\Response(response="201", description="created", @OA\JsonContent(example={
     *      "data":{},
     *      "meta":{}
     *  })),
     *  @OA\Response(response="401", description="unauthorized", @OA\JsonContent(
     *      ref="#/components/schemas/UnauthorizedResponse"
     *  )),
     *  @OA\Response(response=500, description="error to create transaction")
     * )
     */
    public function store(StoreRequest $request): JsonResponse
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
