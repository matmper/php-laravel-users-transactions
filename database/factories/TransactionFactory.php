<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'payer_id' => User::factory()->make()->public_id,
            'payee_id' => User::factory()->make()->public_id,
            'amount' => fake()->randomNumber(3, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
