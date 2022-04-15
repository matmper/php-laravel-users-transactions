<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // verifica se o tipo de conta pode enviar transação
        Gate::define('send-transaction', function (User $user) {
            if ($user->isPf !== true) {
                throw new \Exception("usuários tipo lojistas não podem realizar transações", 403);
            }

            return true;
        });

        // verifica se usuário payer é diferente de usuário payee
        Gate::define('user-is-different', function (User $user, string $payeeId) {
            if ($user->public_id === $payeeId) {
                throw new \Exception("usuários não podem realizar transações para si mesmo", 403);
            }

            return true;
        });
    }
}
