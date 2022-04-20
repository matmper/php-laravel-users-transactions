<?php declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Traits\UserTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use UserTrait;
    use RefreshDatabase;

    /**
     * Realiza um teste no serviço que captura os dados do usuário logado e seu saldo
     *
     * @return void
     */
    public function testGetUserMe(): void
    {
        $this->authUser();

        $response = $this->getJson('/users/me', ['Authorization' => "Bearer {$this->token}"])
            ->assertStatus(200)
            ->content();

        $content = json_decode($response, true);

        $this->assertArrayHasKey('success', $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertArrayHasKey('data', $content);

        $this->assertArrayHasKey('user', $content['data']);
        
        $this->assertIsInt($content['data']['balance']);
    }
}
