<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
        //
    }

    /**
     * Returns personal user data by wallet public id (uuid)
     *
     * @param string $publicId
     * @return User
     */
    public function getUserData(string $publicId): User
    {
        return $this->userRepository->firstOrFail(
            ['public_id' => $publicId],
            ['id', 'name', 'email', 'document_number', 'public_id']
        );
    }
}
