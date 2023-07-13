<?php

namespace App\Exceptions\Requests;

use App\Exceptions\BaseException;
use Illuminate\Http\Response;

class BadRequestException extends BaseException
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    public function __construct(object $errors)
    {
        $this->code = Response::HTTP_BAD_REQUEST;
        $this->message = json_encode($errors);
    }
}
