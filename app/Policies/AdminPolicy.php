<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function crud(User $user): bool
    {
        return $user->isAdmin();
    }
}
