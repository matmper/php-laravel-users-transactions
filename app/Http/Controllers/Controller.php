<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //use DispatchesJobs, ValidatesRequests;

    /**
     * Método para responder um json ao front end com padrão epics
     *
     * @param boolean $success
     * @param string $message
     * @param array|null $data
     * @param integer|null $statusCode
     * @return object
     */
    protected function resp(
        bool $success,
        string $message,
        ?array $data = null,
        ?int $statusCode = 200
    ): object {
        $message = ucfirst($message);

        $statusCode = !$success && $statusCode === 200 ? 404 : $statusCode;

        return response()
            ->json(['success' => $success, 'message' => $message, 'data' => $data], $statusCode);
    }
}
