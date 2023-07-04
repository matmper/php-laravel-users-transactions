<?php

namespace App\Http\Requests\Auth;

use App\Enums\TypeEnum;
use App\Helpers\UtilsHelper;
use App\Http\Requests\BaseRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        $request->document_number = UtilsHelper::onlyNumbers($request->documentNumber);

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
            'type' => ['required', Rule::in(TypeEnum::toArray())],
        ];
    }
}
