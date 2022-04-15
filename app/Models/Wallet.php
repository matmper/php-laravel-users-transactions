<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Wallet
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $transaction_id
 * @property string $name
 * @property int $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @property Transaction|null $transaction
 * @property User $user
 *
 * @package App\Models
 */
class Wallet extends Model
{
    use SoftDeletes;
    protected $table = 'wallets';

    protected $casts = [
        'user_id' => 'int',
        'amount' => 'int'
    ];

    protected $fillable = [
        'user_id',
        'transaction_id',
        'name',
        'amount'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'public_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
