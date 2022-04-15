<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //use DispatchesJobs, ValidatesRequests;

    /**
     * Método para responder um json ao front end com padrão epics
     *
     * @param string $message
     * @param array|null $data
     * @param integer|null $statusCode
     * @return object
     */
    protected function resp(
        string $message,
        ?array $data = null,
        ?int $statusCode = 200
    ): object {
        return response()
            ->json(['success' => true, 'message' => $message, 'data' => $data], $statusCode);
    }
}
