<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function crud(User $user): bool
    {
        return $user->isUser();
    }
}
