<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use App\Models\Transaction;
use Matmper\Repositories\BaseRepository;

final class TransactionRepository extends BaseRepository
{
    /**
     * @var Transaction
     */
    protected $model = Transaction::class;
}
