<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->jsonResponse(
            'List of users',
            UserResource::collection(User::with(['role', 'location'])->get())
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->store($request->validated());

        if (! $user) {
            return $this->jsonResponse(
                'Error while creating user.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse(
            'User created successfully.',
            new UserResource($user->load('retailers')),
        );
    }

    public function show(string $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->jsonResponse(
                'User not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return $this->jsonResponse(data: new UserResource($user->load(['role', 'location', 'retailers'])));
    }

    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->jsonResponse(
                'User not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        if (! $this->userService->update($request->validated(), $user)) {
            return $this->jsonResponse(
                'Error while updating user.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse(
            'User updated successfully.',
            new UserResource($user->load('retailers')),
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->jsonResponse(
                'User not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        if (! $this->userService->destroy($user)) {
            return $this->jsonResponse(
                'Error while deleting user.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse('User deleted successfully.');
    }
}
