<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'payeeId' => ['required', 'string', 'min:36', 'max:36'],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }
}
