<?php declare(strict_types=1);

namespace Tests\Unit;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Realiza um teste no serviço que captura os dados do usuário logado e seu saldo
     *
     * @return void
     */
    public function testGetUserMe(): void
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
                    'deleted_at'
                ],
                'meta' => ['balance']
            ]);
        $this->assertIsInt($content->meta->balance);
    }
}
