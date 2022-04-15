<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreRequest;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService)
    {
    }

    /**
     * Realiza uma nova transação entre usuários
     *
     * @param StoreRequest $request
     * @return object
     */
    public function store(StoreRequest $request): object
    {
        $transaction = $this->transactionService->handler($request->payeeId, $request->amount)
            ->transaction()
            ->message()
            ->toArray();

        return $this->resp('transação realizada com sucesso', $transaction);
    }
}
