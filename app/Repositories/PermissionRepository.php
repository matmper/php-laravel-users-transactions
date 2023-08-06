<?php

/**
 * Created by github.com/matmper/laravel-repository-release
 */

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;
use App\Models\Permission;

final class PermissionRepository extends BaseRepository
{
    /**
     * @var Permission
     */
    protected $model = Permission::class;
}
