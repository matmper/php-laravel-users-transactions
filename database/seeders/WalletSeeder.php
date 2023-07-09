<?php

namespace Database\Seeders;

use App\Enums\TypeEnum;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userPf = User::select('id', 'public_id')->where('type', TypeEnum::PESSOA_FISICA)->firstOrFail();
        $transactionPf = Transaction::factory()->create([
            'payer_id' => $userPf->public_id,
            'payee_id' => $userPf->public_id,
            'amount' => 4950,
        ]);

        $userPj = User::select('id', 'public_id')->where('type', TypeEnum::PESSOA_JURIDICA)->firstOrFail();
        $transactionPj = Transaction::factory()->create([
            'payer_id' => $userPj->public_id,
            'payee_id' => $userPj->public_id,
            'amount' => 150,
        ]);
        
        Wallet::factory(2)->sequence(
            [
                'transaction_id' => $transactionPf->public_id,
                'user_id' => $userPf->id,
                'name' => 'cortesia pf',
                'amount' => 4950,
            ],
            [
                'transaction_id' =>  $transactionPj->public_id,
                'user_id' => $userPj->id,
                'name' => 'cortesia pj',
                'amount' => 150,
            ]
        )->create();
    }
}
