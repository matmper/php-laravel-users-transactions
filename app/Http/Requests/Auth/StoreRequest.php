<?php

namespace App\Http\Requests\Auth;

use App\Enums\TypeEnum;
use App\Helpers\UtilsHelper;
use App\Http\Requests\BaseRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *  schema="AuthStoreRequest",
 *  required={"name", "email", "documentNumber", "password", "type"},
 *  @OA\Property(property="name", type="string", example="User Name Example"),
 *  @OA\Property(property="email", type="email", example="email@example.com"),
 *  @OA\Property(property="documentNumber", type="string", example="11122233344"),
 *  @OA\Property(property="password", type="string", example="mypass"),
 *  @OA\Property(property="type", type="string", example="pf"),
 * )
 */
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
