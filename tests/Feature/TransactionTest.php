<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @test
     */
    public function testPostTransactionStore(): void
    {
        $payer = $this->auth();
        $payee = User::factory()->cnpj()->create();

        $service = app(\App\Services\WalletService::class);

        $initialBalance = fake()->randomNumber(3, true);
        $transactionAmount = fake()->randomNumber(2, true);

        Wallet::factory()->create(['user_id' => $payer->id, 'amount' => $initialBalance]);

        // check balance before transaction
        $balance = $service->getBalance($payer->id);
        $this->assertEquals($balance, $initialBalance, 'initial balance is wrong');

        $response = $this->postJson('/transactions', [
            'payeeId' => $payee->public_id,
            'amount' => $transactionAmount,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
                'data' => [
                    'public_id',
                    'amount',
                    'payer',
                    'payee',
                ],
                'meta' => [
                    'success',
                    'response' => ['message'],
                ],
            ]);

        // check balance after transaction
        $balance = $service->getBalance($payer->id);
        $this->assertEquals($balance, ($initialBalance - $transactionAmount), 'final balance is wrong');
    }
}
