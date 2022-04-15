<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    /**
     * Public construct
     *
     * @param UserRepository $userRepository
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Retorna dados pessoais do usuário para realizar uma transação
     *
     * @param string $publicId
     * @return object
     */
    public function getUserData(string $publicId): object
    {
        return $this->userRepository->firstOrFail(
            ['public_id' => $publicId],
            ['id', 'name', 'email', 'document_number', 'public_id']
        );
    }
}
