<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends MainSeeder
{
    public function run(): void
    {
        DB::table('role_user')->insert([
            'user_id' => User::query()->where('email', config('settings.users.admin.email'))->first()->id,
            'role_id' => Role::query()->where('name', RoleEnum::ADMIN)->first()->id
        ]);
    }
}
