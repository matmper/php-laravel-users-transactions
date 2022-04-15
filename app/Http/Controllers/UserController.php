<?php

namespace App\Http\Controllers;

use App\Services\WalletService;

class UserController extends Controller
{
    public function __construct(protected WalletService $walletService)
    {
    }
    /**
     * Retorna dados do usuário logado
     *
     * @return object
     */
    public function me(): object
    {
        $user = auth()->user();

        return $this->resp(
            'online',
            [
                'user' => $user,
                'balance' => $this->walletService->getBalance($user->id),
            ],
            200
        );
    }
}
