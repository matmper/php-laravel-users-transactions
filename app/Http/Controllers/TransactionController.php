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
     * @OA\POST(
     *  path="/transactions",
     *  summary="Create new transaction",
     *  tags={"Transactions"},
     *  security = {"jwt"},
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *        required={"payeeId","amount"},
     *        @OA\Property(property="payeeId", type="string", format="text", example="31bf19b0-1a2c-11ee-be56-0242ac120002"),
     *        @OA\Property(property="amount", type="numeric", format="text", example="100"),
     *     ),
     *  ),
     *  @OA\Response(response=200, description="transaction created"), 
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

        return ResponseResource::handle($transaction, [], Response::HTTP_OK);
    }
}
