<?php

namespace App\Services;

use App\Repositories\WalletRepository;

class WalletService
{
    public function __construct(protected WalletRepository $walletRepository)
    {
    }

    /**
     * Retorna o saldo do usuário em centavos (100 = R$1,00)
     *
     * @param integer $userId
     * @return integer
     */
    public function getBalance(int $userId): int
    {
        return $this->walletRepository->getBalance($userId);
    }

    /**
     * Realiza a ponta com o repository e insere saldo
     *
     * @param array $data
     * @return object|null
     */
    public function insert(array $data): ?object
    {
        return $this->walletRepository->insert($data);
    }
}
