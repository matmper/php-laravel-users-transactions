<?php

namespace App\Services;

use App\Models\Wallet;
use App\Repositories\WalletRepository;

class WalletService
{
    public function __construct(protected WalletRepository $walletRepository)
    {
    }

    /**
     * Get wallet balance in reais (100 = R$1,00)
     *
     * @param integer $userId
     * @return integer
     */
    public function getBalance(int $userId): int
    {
        return $this->walletRepository->getBalance($userId);
    }

    /**
     * Create a new wallet
     *
     * @param array $data
     * @return Wallet|null
     */
    public function create(array $data): ?Wallet
    {
        return $this->walletRepository->create($data);
    }
}
