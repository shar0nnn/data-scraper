<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json(['message' => 'Wrong email or password.'], 422);
        }
        $user = User::query()->where('email', $request->email)->first();

        if ($user->email === 'parser@gmail.com') {
            return response()->json([
                'message' => 'Logged in successfully as parser.',
                'token' => $user->createToken('parsing', ['scrapedProduct:store'])->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'Logged in successfully.',
            'token' => $user->createToken('api', ['server:crud'])->plainTextToken,
        ]);
    }
}
