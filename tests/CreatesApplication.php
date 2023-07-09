<?php

namespace Tests;

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
     * @return User
     */
    public function auth(array $attributes = [], bool $isPj = false): User
    {
        $user =  $isPj
            ?User::factory()->cnpj()->create($attributes)
            : User::factory()->cpf()->create($attributes);

        $this->actingAs($user);
        
        return $user;
    }

    /**
     * Set database connection to tests
     *
     * @return void
     */
    private function setConnection(): void
    {
        $connection = config('database.default');

        if ($connection !== 'mysql') {
            return;
        }

        $databaseName = config('database.connections.mysql.database', 'userstransactions');
        $databaseName .= '_tests';

        DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName");
        Config::set('database.connections.mysql.database', $databaseName);
    }
}
