<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Request;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        $request->document_number = (string) preg_replace("/[^0-9]/", "", $request->documentNumber);

        return [
            'name' => ['required', 'string', 'min:1', 'max:75'],
            'documentNumber' => [
                'required',
                'string',
                'min:11',
                'max:14',
                'unique:users,document_number',
                'document_number:both'
            ],
            'email' => ['required', 'email', 'unique:users,email', 'max:150'],
            'password' => ['required', 'min:6'],
            'type' => ['required', 'in:pf,pj']
        ];
    }
}