<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $roles = [
            RoleEnum::ADMIN => array_values(PermissionEnum::toArray()),
            RoleEnum::USER => [
                PermissionEnum::AUTH_GET_LOGOUT,
                PermissionEnum::USER_GET_ME,
            ],
            RoleEnum::USER_PF => [
                PermissionEnum::TRANSACTION_POST_STORE,
            ],
            RoleEnum::USER_PJ => [
                //
            ],
        ];

        foreach ($roles as $role => $permissions) {
            collect($permissions)->map(function ($permission) {
                Permission::findOrCreate($permission, 'api');
            });

            Role::findOrCreate($role, 'api')->syncPermissions($permissions);
        }
    }
}