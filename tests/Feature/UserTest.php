<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function test_get_user_me(): void
    {
        $this->auth();

        $response = $this->getJson('/me');
        $content = json_decode($response->content());

        $response->assertStatus(Response::HTTP_OK);
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
                'meta' => [
                    'roles' => [],
                    'permissions' => [],
                    'balance',
                ],
            ]);
        $this->assertIsInt($content->meta->balance);
    }

    /**
     * @test
     */
    public function test_get_user_show(): void
    {
        $this->auth([], [RoleEnum::ADMIN]);

        $user = User::factory()->cpf()->create();

        $response = $this->getJson("/users/{$user->id}");

        $content = json_decode($response->content());

        $response->assertStatus(Response::HTTP_OK);
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
                'meta' => ['roles'],
            ]);

        $this->assertEquals($content->data->id, $user->id);
        $this->assertEquals($content->data->name, $user->name);
    }

    /**
     * @test
     */
    public function test_patch_user_update(): void
    {
        $this->auth([], [RoleEnum::ADMIN]);

        $user = User::factory()->cpf()->create();
        $roles = Role::whereIn('name', [RoleEnum::ADMIN, RoleEnum::USER])->get()->pluck('id');

        $data = [
            'password' => $password = fake()->password(8),
            'password_confirmation' => $password,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'roles' => $roles,
        ];

        $response = $this->patchJson("/users/{$user->id}", $data);

        $content = json_decode($response->content());

        $response->assertStatus(Response::HTTP_OK);
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
                'meta' => ['roles'],
            ]);

        $user = User::findOrFail($user->id);
        
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['name'], $content->data->name);
        $this->assertTrue($user->hasRole([RoleEnum::ADMIN, RoleEnum::USER]));
    }
}
