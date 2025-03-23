<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/api/retailers",
 *     summary="List of retailers",
 *     tags={"Retailer"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\Response(
 *         response=200,
 *         description="List of retailers",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                     ),
 *                     @OA\Property(
 *                         property="title",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="url",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="currency",
 *                         type="object",
 *                         @OA\Property(
 *                             property="id",
 *                             type="integer",
 *                         ),
 *                         @OA\Property(
 *                             property="code",
 *                             type="string",
 *                         ),
 *                         @OA\Property(
 *                             property="description",
 *                             type="string",
 *                         ),
 *                         @OA\Property(
 *                             property="symbol",
 *                             type="string",
 *                         ),
 *                     ),
 *                     @OA\Property(
 *                         property="logo",
 *                         type="string",
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\Post(
 *     path="/api/retailers",
 *     summary="Create retailer",
 *     tags={"Retailer"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "url", "currency_id", "logo"},
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     maxLength=255,
 *                 ),
 *                 @OA\Property(
 *                     property="url",
 *                     type="string",
 *                     maxLength=255,
 *                     format="uri",
 *                 ),
 *                 @OA\Property(
 *                     property="currency_id",
 *                     type="integer",
 *                     description="Must exist in currencies table",
 *                 ),
 *                 @OA\Property(
 *                     property="logo",
 *                     type="string",
 *                     format="binary",
 *                     description="Image file (jpeg, jpg, png, webp, max 5MB)",
 *                 ),
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Retailer created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="url",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="currency",
 *                     type="object",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                     ),
 *                     @OA\Property(
 *                         property="code",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="description",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="symbol",
 *                         type="string",
 *                     ),
 *                 ),
 *                 @OA\Property(
 *                     property="logo",
 *                     type="string",
 *                 ),
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=503,
 *         description="Error while creating retailer",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\Patch(
 *     path="/api/retailers/{retailer}",
 *     summary="Update retailer",
 *     tags={"Retailer"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Retailer ID",
 *         in="path",
 *         name="retailer",
 *         required=true,
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "url", "currency_id"},
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     maxLength=255,
 *                 ),
 *                 @OA\Property(
 *                     property="url",
 *                     type="string",
 *                     maxLength=255,
 *                     format="uri",
 *                 ),
 *                 @OA\Property(
 *                     property="currency_id",
 *                     oneOf={
 *                         @OA\Schema(type="string"),
 *                         @OA\Schema(type="integer"),
 *                     },
 *                     description="Must exist in currencies table",
 *                 ),
 *                 @OA\Property(
 *                     property="logo",
 *                     type="string",
 *                     format="binary",
 *                     description="Image file (jpeg, jpg, png, webp, max 5MB)",
 *                 ),
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Retailer updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="url",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="currency",
 *                     type="object",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                     ),
 *                     @OA\Property(
 *                         property="code",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="description",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="symbol",
 *                         type="string",
 *                     ),
 *                 ),
 *                 @OA\Property(
 *                     property="logo",
 *                     type="string",
 *                 ),
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=503,
 *         description="Error while updating retailer",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Retailer not found",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\Delete(
 *     path="/api/retailers/{retailer}",
 *     summary="Delete retailer",
 *     tags={"Retailer"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Retailer ID",
 *         in="path",
 *         name="retailer",
 *         required=true,
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Retailer deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=503,
 *         description="Error while deleting retailer",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Retailer not found",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\Get(
 *     path="/api/retailers/metrics",
 *     summary="Daily metrics for retailers",
 *     tags={"Retailer"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         name="product_ids",
 *         in="query",
 *         description="Comma separated list of product IDs",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *
 *     @OA\Parameter(
 *         name="retailer_ids",
 *         in="query",
 *         description="Comma separated list of retailer IDs",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *
 *     @OA\Parameter(
 *         name="manufacturer_part_numbers",
 *         in="query",
 *         description="Comma separated list of manufacturer part numbers of products",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *
 *     @OA\Parameter(
 *         name="start_date",
 *         in="query",
 *         description="Start date of the metrics",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             format="date",
 *             example="2025-03-23"
 *         )
 *     ),
 *
 *     @OA\Parameter(
 *         name="end_date",
 *         in="query",
 *         description="End date of the metrics",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             format="date",
 *             example="2025-03-23"
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Array of metrics per retailer per day",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(
 *                         property="retailer_id",
 *                         type="integer",
 *                     ),
 *                     @OA\Property(
 *                         property="average_price",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="average_number_of_images",
 *                         type="integer",
 *                     ),
 *                     @OA\Property(
 *                         property="average_rating",
 *                         type="number",
 *                         format="float",
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 * ),
 */
class RetailerController extends Controller
{
    //
}
