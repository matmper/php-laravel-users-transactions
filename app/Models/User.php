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
 * @property string|null $document_number
 * @property string|null $phone_ddi
 * @property string|null $phone
 * @property int $two_step_auth
 * @property int $status
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|UsersLog[] $users_logs
 *
 * @package App\Models
 */
class User extends Model implements Authenticatable, JWTSubject
{
	use Notifiable;
	use AuthenticableTrait;
	use SoftDeletes;
	protected $table = 'users';

	protected $casts = [

	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'public_id',
		'name',
		'email',
		'document_number',
		'type',
		'password'
	];

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
}
