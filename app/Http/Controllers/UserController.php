<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    /**
     * Retorna dados do usuário logado
     *
     * @return object
     */
    public function me(): object
    {
        return $this->resp(
            true,
            'online',
            [
                'user' => auth()->user(),
                'role' => 1
            ],
            200
        );
    }
}
