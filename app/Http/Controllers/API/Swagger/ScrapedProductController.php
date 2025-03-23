<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Controllers\Controller;

///**
// * @OA\Post(
// *     path="/api/retailers",
// *     summary="Create retailer",
// *     tags={"Retailer"},
// *
// *     @OA\RequestBody(
// *         @OA\JsonContent(
// *             allOf={
// *                 @OA\Schema(
// *                     @OA\Property(property="title", type="string", example="Retailer Title"),
// *                     @OA\Property(property="url", type="string", format="uri", example="http://retailer.url"),
// *                     @OA\Property(property="currency_id", type="integer", example=1),
// *                     @OA\Property(
// *                         property="logo",
// *                         type="string",
// *                         format="binary",
// *                         description="Image file (JPEG, PNG, WEBP)"
// *                     ),
// *                 ),
// *             },
// *         ),
// *     ),
// *     @OA\Response(
// *         response=200,
// *         description="Retailer created successfully",
// *     ),
// *     @OA\Response(
// *         response=503,
// *         description="Error while creating retailer",
// *      ),
// * ),
// */
class ScrapedProductController extends Controller
{
    //
}
