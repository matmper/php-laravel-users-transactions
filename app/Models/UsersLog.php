<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersLog
 * 
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property string $method
 * @property string $ip
 * @property string $agent
 * @property array|null $json
 * @property Carbon $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UsersLog extends Model
{
	protected $table = 'users_logs';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'json' => 'json'
	];

	protected $fillable = [
		'user_id',
		'url',
		'method',
		'ip',
		'agent',
		'json'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
