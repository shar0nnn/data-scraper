<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService
{
    public function store(array $data): User|false
    {
        $retailers = $data['retailers'];

        try {
            DB::beginTransaction();
            $user = User::query()->create($data);

            if (! empty($retailers)) {
                $user->retailers()->attach($retailers);
            }
            DB::commit();

            return $user;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::channel('users')->error($throwable->getMessage());

            return false;
        }
    }

    public function update(array $data, User $user): User|false
    {
        $retailers = $data['retailers'];

        try {
            DB::beginTransaction();
            $user->update($data);
            $user->retailers()->sync($retailers);
            DB::commit();

            return $user;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::channel('users')->error($throwable->getMessage());

            return false;
        }
    }

    public function destroy(User $user): bool
    {
        try {
            DB::beginTransaction();
            $user->retailers()->detach();
            $user->products()->detach();
            $user->delete();
            DB::commit();

            return true;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::channel('users')->error($throwable->getMessage());

            return false;
        }
    }
}
