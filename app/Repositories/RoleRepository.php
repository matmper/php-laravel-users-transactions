<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;
use App\Models\Role;

final class RoleRepository extends BaseRepository
{
    /**
     * @var Role
     */
    protected $model = Role::class;
}
