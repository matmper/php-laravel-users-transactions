<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Determine a failed jason response
     * @param  Validator $validator
     * @return string json
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response([
            'success' => false,
            'message' => 'payload is invalid',
            'data' => $validator->errors(),
            'datetime' => date('Y-m-d H:i:s')
        ], 400);

        throw new HttpResponseException($response);
    }
}
