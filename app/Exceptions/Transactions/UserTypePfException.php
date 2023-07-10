<?php

namespace App\Exceptions\Transactions;

use App\Exceptions\BaseException;
use Illuminate\Http\Response;

class UserTypePfException extends BaseException
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    public function __construct()
    {
        $this->code = Response::HTTP_FORBIDDEN;
        $this->message =  'Merchant type (PF) users cannot create transactions';
    }
}
