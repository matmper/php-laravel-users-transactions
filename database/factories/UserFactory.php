<?php

namespace Database\Factories;

use App\Enums\TypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'name' => fake()->name(),
            'document_number' => rand(11111111111, 99999999999),
            'email' => fake()->unique()->safeEmail(),
            'type' => TypeEnum::PESSOA_FISICA,
            'password' => Hash::make(fake()->password()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
