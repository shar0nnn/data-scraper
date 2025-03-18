<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'status' => false,
                'message' => 'Wrong email or password.'
            ], 422);
        }
        $user = User::query()->where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'Logged in successfully.',
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }
}
