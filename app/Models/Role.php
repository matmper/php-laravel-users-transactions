<?php

namespace App\Models;

use Spatie\Permission\Models\Role as ModelsRole;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Role extends ModelsRole
{
    protected $table = 'roles';

    protected $fillable = [
        'id',
        'name',
        'guard_name',
    ];
}
