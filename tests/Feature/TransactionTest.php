<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @test
     */
    public function test_post_transaction_store(): void
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

        $response = $this->postJson('/transactions', ['payeeId' => $payee->public_id, 'amount' => $transactionAmount]);

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

    /**
     * @test
     */
    public function test_post_transaction_store_unauthorized_exception(): void
    {
        $payer = $this->auth([], [RoleEnum::USER, RoleEnum::USER_PJ], true);
        $payee = User::factory()->cpf()->create();

        $initialBalance = fake()->randomNumber(3, true);
        $transactionAmount = fake()->randomNumber(2, true);

        Wallet::factory()->create(['user_id' => $payer->id, 'amount' => $initialBalance]);

        $this->expectException(\Spatie\Permission\Exceptions\UnauthorizedException::class);

        $this->postJson('/transactions', ['payeeId' => $payee->public_id, 'amount' => $transactionAmount]);
    }
}
