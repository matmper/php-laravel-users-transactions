<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;
use Carbon\Carbon;

class WalletSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        return DB::table('wallets')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'cortesia pf',
                'amount' => 4950,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'id' => 2,
                'user_id' => 2,
                'name' => 'cortesia pj',
                'amount' => 150,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
