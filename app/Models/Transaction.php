<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property string $public_id
 * @property string $payer_id
 * @property string $payee_id
 * @property int $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|Wallet[] $wallets
 *
 * @package App\Models
 */
class Transaction extends Model
{
	use SoftDeletes;
	protected $table = 'transactions';

	protected $casts = [
		'amount' => 'int'
	];

	protected $fillable = [
		'public_id',
		'payer_id',
		'payee_id',
		'amount'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'payer_id', 'public_id');
	}

	public function wallets()
	{
		return $this->hasMany(Wallet::class, 'transaction_id', 'public_id');
	}
}
