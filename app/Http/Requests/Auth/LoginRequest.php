<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
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
                'max:14'
            ],
            'password' => ['required', 'min:6']
        ];
    }
}