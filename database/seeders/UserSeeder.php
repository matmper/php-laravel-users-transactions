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
        $users = User::factory(3)->sequence(
            [
                'public_id' => '34c65f52-a84c-440a-859e-77e16b6310d9',
                'document_number' => '11122233300',
                'password' => Hash::make('mypass'), 
                'type' => TypeEnum::PESSOA_FISICA
            ],
            [
                'public_id' => '67dabd7d-f925-410e-8a62-d516e5f94c8b',
                'document_number' => '11122233301',
                'password' => Hash::make('mypass'), 
                'type' => TypeEnum::PESSOA_FISICA
            ],
            [
                'public_id' => '18a32084-26c2-41e4-afb7-b378bced6531',
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

        foreach ($users as $key => $user) {
            $user->assignRole($roles[$key]);
        }
    }
}
