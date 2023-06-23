<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->make();

        return [
            'user_id' => $user->id,
            'transaction_id' => Transaction::factory()->make(['payee_id' => $user->public_id]),
            'name' => fake()->text(20),
            'amount' => fake()->randomNumber(3, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
