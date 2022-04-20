<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

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
            'name' => $this->faker->name(),
            'document_number' => rand(11111111111, 99999999999),
            'email' => $this->faker->unique()->safeEmail(),
            'type' => 'pf',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
