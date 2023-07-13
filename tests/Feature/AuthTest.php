<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
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
    public function test_post_auth_login(): void
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
                    'roles' => [],
                    'permissions' => [],
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_post_auth_register(): void
    {
        $user = User::factory()->cpf()->make();

        $response = $this->postJson('/register', [
            'documentNumber' => $user->document_number,
            'password' => $password = fake()->password(8),
            'password_confirmation' => $password,
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
    public function test_get_auth_logout(): void
    {
        $password = fake()->password(8);

        $user = User::factory()->cpf()->create(['password' => Hash::make($password)]);
        $user->assignRole(RoleEnum::USER);

        $auth = $this->postJson('/login', [
            'documentNumber' => $user->document_number,
            'password' => $password,
        ]);
        $auth = json_decode($auth->content());

        $response = $this->getJson('/logout', ['Authorization' => "Bearer {$auth->data->access_token}"]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
                'data' => ['success'],
                'meta' => [],
            ]);
    }
}
