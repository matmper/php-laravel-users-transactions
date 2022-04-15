<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    /**
     * @var Model
     */
    protected $model = Transaction::class;
}
