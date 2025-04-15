<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService
{
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
