<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\StoreRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository) {
    }

    /**
     * Realiza o login e cria sessão de um novo usuário
     *
     * @return object
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->documentNumber
            ? ['document_number' => onlyNumbers($request->documentNumber)]
            : ['email' => $request->email];

        $credentials['password'] = $request->password;

        if (!$token = Auth::attempt($credentials)) {
            return $this->resp(false, 'usuário ou senha inválido', statusCode:401);
        }

        $jwt = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
 
        return $this->resp(true, 'usuário encontrado', ['user' => auth()->user(), 'token' => $jwt]);
    }

    /**
     * Realiza o cadastro de um novo usuário
     *
     * @return object
     */
    public function store(StoreRequest $request)
    {
        $user = $this->userRepository->insert([
            'public_id' => \Illuminate\Support\Str::uuid()->toString(),
            'name' => ucwords($request->name),
            'email' => strtolower($request->email),
            'document_number' => onlyNumbers($request->documentNumber),
            'password' => Hash::make($request->password)
        ]);

        if (empty($user)) {
            throw new \Exception("erro ao salvar usuário", 500);
        }

        return $this->resp(true, 'registrado com sucesso', ['user' => $user]);
    }

    /**
     * Logout, realiza o fim da sessão
     *
     * @return object
     */
    public function logout(): object
    {
        if (auth()->logout(true)) {
            return $this->resp(true, 'sessão encerrada com sucesso');
        }

        return $this->resp(false, 'houve um erro ao encerrar sessão');
    }
}