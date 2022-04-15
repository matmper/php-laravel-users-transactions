<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\StoreRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Realiza o login e cria sessão de um novo usuário
     *
     * @param LoginRequest $request
     * @return object
     */
    public function login(LoginRequest $request): object
    {
        $token = Auth::attempt([
            'document_number' => \App\Helpers\UtilsHelper::onlyNumbers($request->documentNumber),
            'password' => $request->password
        ]);

        if (empty($token)) {
            throw new \Exception('document number or password is invalid', 401);
        }

        $jwt = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
 
        return $this->resp('usuário encontrado', ['user' => auth()->user(), 'token' => $jwt]);
    }

    /**
     * Realiza o cadastro de um novo usuário
     *
     * @param StoreRequest $request
     * @return object
     */
    public function store(StoreRequest $request): object
    {
        $user = $this->userRepository->insert([
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'name' => ucwords($request->name),
            'email' => strtolower($request->email),
            'document_number' => \App\Helpers\UtilsHelper::onlyNumbers($request->documentNumber),
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        if (empty($user)) {
            throw new \Exception("erro ao salvar usuário", 500);
        }

        return $this->resp('registrado com sucesso', ['user' => $user]);
    }

    /**
     * Logout, realiza o fim da sessão
     *
     * @return object
     */
    public function logout(): object
    {
        if (auth()->logout()) {
            return $this->resp('sessão encerrada com sucesso');
        }

        throw new \Exception('houve um erro ao encerrar sessão', 500);
    }
}
