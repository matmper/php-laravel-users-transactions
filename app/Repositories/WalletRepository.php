<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use App\Models\Wallet;
use Matmper\Repositories\BaseRepository;

final class WalletRepository extends BaseRepository
{
    /**
     * @var Wallet
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
