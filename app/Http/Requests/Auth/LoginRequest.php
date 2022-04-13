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
            'email' => ['nullable', 'email', 'max:150', 'required_without:documentNumber'],
            'documentNumber' => [
                'nullable',
                'string',
                'min:11',
                'max:21',
                'document_number:both',
                'required_without:email'
            ],
            'password' => ['required', 'min:6'],
            'companyPublicId' => ['nullable', 'string', 'min:36', 'max:36']
        ];
    }
}