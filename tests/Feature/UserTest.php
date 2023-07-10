<?php declare(strict_types=1);

namespace Tests\Feature;

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
}
