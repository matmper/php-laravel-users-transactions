<?php

namespace App\Exceptions\Transactions;

use App\Exceptions\BaseException;
use Illuminate\Http\Response;

class InsufficienteBalanceException extends BaseException
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
        $this->message =  'Insufficiente balance to transaction';
    }
}
