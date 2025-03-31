<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function jsonResponse(string $message = '', $data = null, int $status = 200, array $meta = []): JsonResponse
    {
        $payload = [];
        if (!empty($message)) {
            $payload['message'] = __("messages.$message");
        }
        if (!empty($data)) {
            $payload['data'] = $data;
        }
        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }
}
