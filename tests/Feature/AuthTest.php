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
    public function testPostAuthLogin(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->cpf()->create(['password' => Hash::make($password)]);

        $response = $this->postJson('/login', [
            'documentNumber' => $user->document_number,
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

    /**
     * @test
     */
    public function testPostAuthRegister(): void
    {
        $user = User::factory()->cpf()->make();

        $response = $this->postJson('/register', [
            'documentNumber' => $user->document_number,
            'password' => fake()->password(8),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'type' => TypeEnum::PESSOA_FISICA,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
                'data' => [
                    'id',
                    'public_id',
                    'name',
                    'email',
                    'document_number',
                    'type',
                    'created_at',
                    'updated_at',
                ],
                'meta' => [],
            ]);
    }

    /**
     * @test
     */
    public function testGetAuthLogout(): void
    {
        $password = fake()->password(8);

        $user = User::factory()->cpf()->create(['password' => Hash::make($password)]);

        $response = $this->postJson('/login', [
            'documentNumber' => $user->document_number,
            'password' => $password,
        ]);
        $response = json_decode($response->content());

        $response = $this->getJson('/logout', ['Authorization' => "Bearer {$response->data->access_token}"]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
                'data' => ['success'],
                'meta' => [],
            ]);
    }
}
