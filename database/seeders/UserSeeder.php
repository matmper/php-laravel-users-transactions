<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;
use Carbon\Carbon;
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
        return DB::table('users')->insert([
            [
                'id' => 1,
                'public_id' => \Illuminate\Support\Str::uuid()->toString(),
                'name' => 'João das Neves',
                'document_number' => '11122233344',
                'email' => 'joao@teste.com',
                'type' => 'pf',
                'password' => Hash::make('mypass'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id' => 2,
                'public_id' => \Illuminate\Support\Str::uuid()->toString(),
                'name' => 'Lojinha Silva',
                'document_number' => '11222333000144',
                'email' => 'loja@teste.com',
                'type' => 'pj',
                'password' => Hash::make('mypass'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
