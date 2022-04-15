<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $public_id
 * @property string $name
 * @property string $email
 * @property string $document_number
 * @property string $password
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Transaction[] $transactions
 * @property Collection|Wallet[] $wallets
 *
 * @package App\Models
 */
class User extends Model implements Authenticatable, JWTSubject
{
	use Notifiable;
	use AuthenticableTrait;
	use SoftDeletes;
	protected $table = 'users';

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'public_id',
		'name',
		'email',
		'document_number',
		'password',
		'type'
	];

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'payer_id', 'public_id');
	}

	public function wallets()
	{
		return $this->hasMany(Wallet::class);
	}

	/**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

	/**
	 * Retorna true se a pessoa for pessoa física
	 *
	 * @return boolean
	 */
	public function getIsPfAttribute(): bool
	{
		return $this->type === 'pf';
	}

	/**
	 * Retorna true se a pessoa for pessoa jurídica
	 *
	 * @return boolean
	 */
	public function getIsPjAttribute(): bool
	{
		return $this->type === 'pj';
	}
}
