<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;
use App\Models\User;

final class UserRepository extends BaseRepository
{
    /**
     * @var User
     */
    protected $model = User::class;
}
