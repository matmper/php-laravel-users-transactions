<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository extends BaseRepository
{
    /**
     * @var Model
     */
    protected $model = Wallet::class;

    /**
     * Get and sum user wallet balance
     *
     * @param integer $userId
     * @return integer
     */
    public function getBalance(int $userId): int
    {
        return $this->model
            ->where('wallets.user_id', $userId)
            ->sum('wallets.amount');
    }
}
