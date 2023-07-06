<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\TypeEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * @test
     */
    public function testGetAuthLogin(): void
    {
        $this->auth();

        $documentNumber = $this->generateFakeCpf();
        $password = fake()->password(8);

        User::factory()->create([
            'document_number' => $documentNumber,
            'password' => Hash::make($password),
            'type' => TypeEnum::PESSOA_FISICA,
        ]);

        $response = $this->postJson('/login', [
            'documentNumber' => $documentNumber,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in'
                ],
                'meta' => [
                    'user' => [
                        'id',
                        'public_id',
                        'name',
                        'email',
                        'document_number',
                        'type',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }
}
