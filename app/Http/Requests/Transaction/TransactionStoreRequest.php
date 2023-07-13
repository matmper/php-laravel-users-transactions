<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseRequest;

/**
 * @OA\Schema(
 *  schema="TransactionStoreRequest",
 *  required={"payeeId","amount"},
 *  @OA\Property(property="payeeId", type="string", format="text", example="31bf19b0-1a2c-11ee-be56-0242ac120002"),
 *  @OA\Property(property="amount", type="numeric", format="text", example="100"),
 * )
 */
class TransactionStoreRequest extends BaseRequest
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
