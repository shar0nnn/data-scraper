<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/api/products",
 *     summary="List of products",
 *     tags={"Product"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Number of page for pagination",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="List of paginated products",
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
 *                         property="description",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="manufacturer_part_number",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="pack_size",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="images",
 *                         type="array",
 *                         @OA\Items(
 *                             @OA\Property(
 *                                 property="link",
 *                                 type="string",
 *                             ),
 *                         ),
 *                     ),
 *                 ),
 *             ),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(
 *                     property="first",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="last",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="prev",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="next",
 *                     type="string",
 *                 ),
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(
 *                     property="current_page",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property="from",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property="last_page",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property="links",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(
 *                             property="url",
 *                             type="string",
 *                         ),
 *                         @OA\Property(
 *                             property="label",
 *                             type="string",
 *                         ),
 *                         @OA\Property(
 *                             property="active",
 *                             type="boolean",
 *                         ),
 *                     ),
 *                 ),
 *                 @OA\Property(
 *                     property="path",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="per_page",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property="to",
 *                     type="integer",
 *                 ),
 *                  @OA\Property(
 *                     property="total",
 *                     type="integer",
 *                 ),
 *             ),
 *         ),
 *     ),
 * ),
 *
 * @OA\Post(
 *     path="/api/products",
 *     summary="Create product",
 *     tags={"Product"},
 *     security={{"bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "pack_size_id", "manufacturer_part_number", "images"},
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     maxLength=255,
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="pack_size_id",
 *                     type="integer",
 *                     description="Must exist in pack_sizes table",
 *                 ),
 *                 @OA\Property(
 *                     property="retailers",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(
 *                             property="id",
 *                             type="integer",
 *                             description="Must exist in retailers table",
 *                         ),
 *                         @OA\Property(
 *                             property="url",
 *                             type="string",
 *                             format="uri",
 *                             maxLength=1000,
 *                         ),
 *                     ),
 *                 ),
 *                 @OA\Property(
 *                     property="manufacturer_part_number",
 *                     type="string",
 *                     maxLength=255,
 *                 ),
 *                 @OA\Property(
 *                     property="images",
 *                     type="array",
 *                     minItems=1,
 *                     @OA\Items(
 *                         type="string",
 *                         format="uri",
 *                         description="URL зображення"
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Product created successfully",
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
 *                     property="description",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="manufacturer_part_number",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="pack_size",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="images",
 *                     type="object",
 *                     @OA\Property(
 *                         property="link",
 *                         type="string",
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response=503,
 *         description="Error while creating product",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 * ),
 */
// * @OA\Patch(
// *     path="/api/retailers/{retailer}",
// *     summary="Update retailer",
// *     tags={"Retailer"},
// *     @OA\Parameter(
// *         description="Retailer ID",
// *         in="path",
// *         name="retailer",
// *         required=true,
// *     ),
// *
// *     @OA\RequestBody(
// *         required=true,
// *         @OA\MediaType(
// *             mediaType="multipart/form-data",
// *             @OA\Schema(
// *                 required={"title", "url", "currency_id"},
// *                 @OA\Property(
// *                     property="title",
// *                     type="string",
// *                     maxLength=255,
// *                 ),
// *                 @OA\Property(
// *                     property="url",
// *                     type="string",
// *                     maxLength=255,
// *                     format="uri",
// *                 ),
// *                 @OA\Property(
// *                     property="currency_id",
// *                     oneOf={
// *                         @OA\Schema(type="string"),
// *                         @OA\Schema(type="integer"),
// *                     },
// *                     description="Must exist in currencies table",
// *                 ),
// *                 @OA\Property(
// *                     property="logo",
// *                     type="string",
// *                     format="binary",
// *                     description="Image file (jpeg, jpg, png, webp, max 5MB)",
// *                 ),
// *             ),
// *         ),
// *     ),
// *
// *     @OA\Response(
// *         response=200,
// *         description="Retailer updated successfully",
// *         @OA\JsonContent(
// *             @OA\Property(
// *                 property="message",
// *                 type="string",
// *             ),
// *             @OA\Property(
// *                 property="data",
// *                 type="object",
// *                 @OA\Property(
// *                     property="id",
// *                     type="integer",
// *                 ),
// *                 @OA\Property(
// *                     property="title",
// *                     type="string",
// *                 ),
// *                 @OA\Property(
// *                     property="url",
// *                     type="string",
// *                 ),
// *                 @OA\Property(
// *                     property="currency",
// *                     type="object",
// *                     @OA\Property(
// *                         property="id",
// *                         type="integer",
// *                     ),
// *                     @OA\Property(
// *                         property="code",
// *                         type="string",
// *                     ),
// *                     @OA\Property(
// *                         property="description",
// *                         type="string",
// *                     ),
// *                     @OA\Property(
// *                         property="symbol",
// *                         type="string",
// *                     ),
// *                 ),
// *                 @OA\Property(
// *                     property="logo",
// *                     type="string",
// *                 ),
// *             ),
// *         ),
// *     ),
// *
// *     @OA\Response(
// *         response=503,
// *         description="Error while updating retailer",
// *         @OA\JsonContent(
// *             @OA\Property(
// *                 property="message",
// *                 type="string",
// *             ),
// *         ),
// *     ),
// *
// *     @OA\Response(
// *         response=404,
// *         description="Retailer not found",
// *         @OA\JsonContent(
// *             @OA\Property(
// *                 property="message",
// *                 type="string",
// *             ),
// *         ),
// *     ),
// * ),
// *
// * @OA\Delete(
// *     path="/api/retailers/{retailer}",
// *     summary="Delete retailer",
// *     tags={"Retailer"},
// *     @OA\Parameter(
// *         description="Retailer ID",
// *         in="path",
// *         name="retailer",
// *         required=true,
// *     ),
// *
// *     @OA\Response(
// *         response=200,
// *         description="Retailer deleted successfully",
// *         @OA\JsonContent(
// *             @OA\Property(
// *                 property="message",
// *                 type="string",
// *             ),
// *         ),
// *     ),
// *
// *     @OA\Response(
// *         response=503,
// *         description="Error while deleting retailer",
// *         @OA\JsonContent(
// *             @OA\Property(
// *                 property="message",
// *                 type="string",
// *             ),
// *         ),
// *     ),
// *
// *     @OA\Response(
// *         response=404,
// *         description="Retailer not found",
// *         @OA\JsonContent(
// *             @OA\Property(
// *                 property="message",
// *                 type="string",
// *             ),
// *         ),
// *     ),
// * ),
// */
class ProductController extends Controller
{
    //
}
