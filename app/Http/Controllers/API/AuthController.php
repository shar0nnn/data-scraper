<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return $this->jsonResponse(
                'Wrong email or password.',
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = User::query()->where('email', $request->input('email'))->first();

        if ($user->email === config('settings.users.parser.email')) {
            return $this->jsonResponse(
                'Logged in successfully as parser.',
                ['token' => $user->createToken('parsing', ['scrapedProduct:store'])->plainTextToken],
            );
        }

        return $this->jsonResponse(
            'Logged in successfully.',
            ['token' => $user->createToken('api', ['server:crud'])->plainTextToken]
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->jsonResponse('Logged out successfully.');
    }

    public function isCurrentUserAdmin(Request $request): JsonResponse
    {
        return $this->jsonResponse(data: $request->user()->isAdmin());
    }
}