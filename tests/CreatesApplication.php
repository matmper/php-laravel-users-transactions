<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Authenticate with a factory user
     *
     * @param array $attributes
     * @return User
     */
    public function auth(array $attributes = []): User
    {
        $user = User::factory()->cpf()->create($attributes);

        $this->actingAs($user);
        
        return $user;
    }
}
