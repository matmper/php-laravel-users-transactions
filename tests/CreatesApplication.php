<?php

namespace Tests;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->setConnection();

        return $app;
    }

    /**
     * Authenticate with a factory user
     *
     * @param array $attributes
     * @param array $roles
     * @param boolean $isPj
     * @return User
     */
    public function auth(array $attributes = [], array $roles = [], bool $isPj = false): User
    {
        $user =  $isPj
            ? User::factory()->cnpj()->create($attributes)
            : User::factory()->cpf()->create($attributes);

        $this->actingAs($user);

        $user->assignRole($roles ?: RoleEnum::ADMIN);
        
        return $user;
    }

    /**
     * Set database connection to tests
     *
     * @return void
     */
    private function setConnection(): void
    {
        $databaseName = config('database.connections.mysql_test.database');

        DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName");
        Config::set('database.default', 'mysql_test');
    }
}
