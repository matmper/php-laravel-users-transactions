<?php

namespace Database\Factories;

use App\Enums\TypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'document_number' => $this->generateFakeCpf(),
            'email' => fake()->unique()->safeEmail(),
            'type' => TypeEnum::PESSOA_FISICA,
            'password' => Hash::make(fake()->password()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the user is cpf (pf)
     */
    public function cpf(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'document_number' => $this->generateFakeCpf(),
                'type' => TypeEnum::PESSOA_FISICA,
            ];
        });
    }

    /**
     * Indicate that the user is cnpj (pj)
     */
    public function cnpj(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'document_number' => $this->generateFakeCnpj(),
                'type' => TypeEnum::PESSOA_JURIDICA,
            ];
        });
    }

    /**
     * Generate a valid and random CPF document number
     * @return string
     */
    private function generateFakeCpf(): string
    {
        for ($i = 1; $i <= 9; $i++) { 
            ${"n{$i}"} = rand(0, 9);
        }
        
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - $this->calculateDigit($d1, 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - $this->calculateDigit($d2, 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }
        
        return $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
    }

    /**
     * Generate a valid and random CNPJ document number
     * @return string
     */
    private function generateFakeCnpj(): string
    {
        for ($i = 1; $i <= 8; $i++) { 
            ${"n{$i}"} = rand(0, 9);
        }

        $n9 = $n10 = $n11 = 0;
        $n12 = 1;

        $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d1 = 11 - $this->calculateDigit($d1, 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
        $d2 = 11 - $this->calculateDigit($d2, 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }

        return $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;
    }

    /**
     * @param int $dividend
     * @param int $dividder
     * @return float
     */
    private function calculateDigit(int $dividend, int $dividder): float
    {
        return round($dividend - (floor($dividend / $dividder) * $dividder));
    }
}
