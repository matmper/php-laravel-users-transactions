<?php

namespace App\Exceptions\Transactions;

use App\Exceptions\BaseException;
use Illuminate\Http\Response;

class BankAuthorizeException extends BaseException
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    public function __construct(int $responseStatusCode)
    {
        $this->code = Response::HTTP_FORBIDDEN;
        $this->message =  'Transaction not authorized into bank api: ' . $responseStatusCode;
    }
}
