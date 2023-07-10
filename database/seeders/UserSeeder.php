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
        User::factory(2)->sequence(
            [
                'document_number' => '11122233344',
                'password' => Hash::make('mypass'), 
                'type' => TypeEnum::PESSOA_FISICA
            ],
            [
                'document_number' => '11222333000144',
                'password' => Hash::make('mypass'),
                'type' => TypeEnum::PESSOA_JURIDICA
            ]
        )->create();

        $users = User::get();

        foreach ($users as $user) {
            switch ($user->type) {
                case TypeEnum::PESSOA_FISICA:
                    $user->assignRole([RoleEnum::USER, RoleEnum::USER_PF]);
                    break;
                case TypeEnum::PESSOA_JURIDICA:
                    $user->assignRole([RoleEnum::USER, RoleEnum::USER_PJ]);
                    break;
            }
        }
    }
}
