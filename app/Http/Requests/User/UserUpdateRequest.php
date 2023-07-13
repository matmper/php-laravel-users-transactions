<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

/**
 * @OA\Schema(
 *  schema="UserUpdateRequest",
 *  required={"name", "email", "roles"},
 *  @OA\Property(property="name", type="string", example="User Name Example"),
 *  @OA\Property(property="email", type="email", example="email@example.com"),
 *  @OA\Property(property="roles", type="array", example={1,3,5}, items={}),
 *  @OA\Property(property="password", type="string", example="mypass"),
 *  @OA\Property(property="password_confirmation", type="string", example="mypass"),
 * )
 */
class UserUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:75'],
            'email' => ['required', 'email', "unique:users,email,{$this->id}", 'max:150'],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'roles' => ['present', 'array'],
            'roles.*' => ['int', 'exists:roles,id'],
        ];
    }
}
