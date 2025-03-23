<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\PathItem(
 *     path="/api/"
 * ),
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0"
 * ),
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *     ),
 * ),
 */
class MainController extends Controller
{
    //
}
