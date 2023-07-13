<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

/**
 * @OA\Schema(
 *  schema="AuthAuthLoginRequest",
 *  required={"documentNumber","password"},
 *  @OA\Property(property="documentNumber", type="string", format="text", example="11122233301"),
 *  @OA\Property(property="password", type="string", format="text", example="mypass"),
 * )
 */
class AuthLoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'documentNumber' => [
                'required',
                'string',
                'min:11',
                'max:14',
            ],
            'password' => ['required', 'min:6']
        ];
    }
}
