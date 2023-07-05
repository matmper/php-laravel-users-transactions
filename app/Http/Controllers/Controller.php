<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Users Transactions API",
 *     version="0.1"
 * ),
 *  @OA\Server(
 *      description="Development in local server",
 *      url="http://localhost:80"
 *  ),
 * @OA\SecurityScheme(
 *      securityScheme="bearer",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="Bearer",
 *      bearerFormat="JWT",
 * ),
 */
class Controller extends BaseController
{
    use ValidatesRequests;
}
