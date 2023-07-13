<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as ModelsRole;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Permission extends ModelsRole
{
    protected $table = 'permissions';

    protected $fillable = [
        'id',
        'name',
        'guard_name',
    ];
}
