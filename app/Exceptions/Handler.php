<?php

namespace App\Exceptions;

use App\Http\Resources\ResponseResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception, $data = [])
    {
        switch (get_class($exception)) {
            case ModelNotFoundException::class:
                throw new \Exception("result not found", Response::HTTP_NOT_FOUND);
                break;
        }

        $meta = ['code' => $exception->getCode()];

        if (config('app.debug')) {
            $meta['file'] = $exception->getFile();
            $meta['line'] = $exception->getLine();
            $meta['trace'] = $exception->getTrace();
        }

        $getCode = $exception->getCode();
        $getCode = $getCode && in_array($getCode, array_keys(Response::$statusTexts)) ? $getCode : 500;

        return ResponseResource::error($exception->getMessage(), $meta, $getCode);
    }
}
