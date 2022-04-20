<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait UserTrait
{
    /**
     * Adiciona o usuário via factory
     *
     * @var User
     */
    public $user;

    /**
     * Adiciona o JWT do usuário
     *
     * @var string
     */
    public $token;

    /**
     * Realiza o setup de um novo usuário
     *
     * @param array $attributes
     * @return self
     */
    public function authUser(array $attributes = []): self
    {
        $this->logout();
      
        $this->user = User::factory()->create($attributes);
        $this->token = null;

        $this->login();
        
        return $this;
    }

    /**
     * Define o passaporte de testes do usuário
     *
     * @return void
     */
    private function login(): void
    {
        $this->token = Auth::login($this->user);
    }
    
    /**
     * Realiza o logout do usuário nos testes
     *
     * @return void
     */
    private function logout(): void
    {
        $this->user = null;
    }
}
