<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class BaseException extends Exception
{
    /**
     * HTTP Status Code
     *
     * @var int
     */
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * Exception message
     *
     * @var string
     */
    protected $message = 'Unknow error';
}
