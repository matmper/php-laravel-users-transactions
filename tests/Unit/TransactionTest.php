<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use App\Services\TransactionService;
use App\Services\WalletService;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * Transaction success
     */
    public function test_transaction_service_store(): void
    {
        $user = $this->auth();
        $userPayee = User::factory()->cnpj()->create();

        $balance = fake()->randomNumber(3, true);
        $balancePayee = fake()->randomNumber(4, true);
        $transactionAmount = fake()->randomNumber(2, true);

        Wallet::factory()->create(['user_id' => $user->id, 'amount' => $balance]);
        Wallet::factory()->create(['user_id' => $userPayee->id, 'amount' => $balancePayee]);

        $transactionService = app(TransactionService::class);
        $walletService = app(WalletService::class);

        $transactionService->handler($userPayee->public_id, $transactionAmount)
            ->transaction()
            ->message();

        $finalBalancePf = $walletService->getBalance($user->id);
        $this->assertEquals($finalBalancePf, ($balance - $transactionAmount), 'final pf balance is wrong');

        $finalBalancePj = $walletService->getBalance($userPayee->id);
        $this->assertEquals($finalBalancePj, ($balancePayee + $transactionAmount), 'final pj balance is wrong');
    }

    /**
     * Transaction exception: balance
     */
    public function test_transaction_service_store_balance_exception(): void
    {
        $user = $this->auth();
        $userPayee = User::factory()->cnpj()->create();

        $balance = fake()->randomNumber(2, true);
        $transactionAmount = fake()->randomNumber(3, true);

        Wallet::factory()->create(['user_id' => $user->id, 'amount' => $balance]);

        $transactionService = app(TransactionService::class);

        $this->expectException(\App\Exceptions\Transactions\InsufficienteBalanceException::class);

        $transactionService->handler($userPayee->public_id, $transactionAmount)
            ->transaction()
            ->message();
    }

    /**
     * Transaction exception: user type pj cant create transacion
     */
    public function test_transaction_service_store_send_transaction_exception(): void
    {
        $user = $this->auth(isPj:true);
        $userPayee = User::factory()->cpf()->create();

        $balance = fake()->randomNumber(3, true);
        $transactionAmount = fake()->randomNumber(2, true);

        Wallet::factory()->create(['user_id' => $user->id, 'amount' => $balance]);

        $transactionService = app(TransactionService::class);

        $this->expectException(\App\Exceptions\Transactions\UserTypePfException::class);

        $transactionService->handler($userPayee->public_id, $transactionAmount)
            ->transaction()
            ->message();
    }
}
