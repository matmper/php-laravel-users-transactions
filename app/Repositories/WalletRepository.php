<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository extends BaseRepository
{
    /**
     * @var Model
     */
    protected $model = Wallet::class;
}