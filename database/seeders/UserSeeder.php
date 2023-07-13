<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\TypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(3)->sequence(
            [
                'document_number' => '11122233300',
                'password' => Hash::make('mypass'), 
                'type' => TypeEnum::PESSOA_FISICA
            ],
            [
                'document_number' => '11122233301',
                'password' => Hash::make('mypass'), 
                'type' => TypeEnum::PESSOA_FISICA
            ],
            [
                'document_number' => '11222333000101',
                'password' => Hash::make('mypass'),
                'type' => TypeEnum::PESSOA_JURIDICA
            ]
        )->create();
        
        $roles = [
            [RoleEnum::ADMIN],
            [RoleEnum::USER, RoleEnum::USER_PF],
            [RoleEnum::USER, RoleEnum::USER_PJ],
        ];

        $users = User::get();

        foreach ($users as $key => $user) {
            $user->assignRole($roles[$key]);
        }
    }
}
