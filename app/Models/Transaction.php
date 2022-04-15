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
 * @property int $user_id
 * @property int $user_id_to
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
		'user_id' => 'int',
		'user_id_to' => 'int',
		'amount' => 'int'
	];

	protected $fillable = [
		'public_id',
		'user_id',
		'user_id_to',
		'amount'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id_to');
	}

	public function wallets()
	{
		return $this->hasMany(Wallet::class);
	}
}
