<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function jsonResponse(
        string $message = '',
               $data = null,
        int    $status = Response::HTTP_OK,
        array  $meta = [],
        array  $messagePlaceholders = []
    ): JsonResponse
    {
        $payload = [];
        if (! empty($message)) {
            $payload['message'] = __("messages.$message", $messagePlaceholders);
        }
        if (! empty($data)) {
            $payload['data'] = $data;
        }
        if (! empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }
}
