<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('MOTOFIX_ADMIN_EMAIL', 'admin@motofix.test')],
            [
                'name' => 'Admin Motofix',
                'password' => Hash::make(env('MOTOFIX_ADMIN_PASSWORD', 'ChangeMe123!')),
                'role' => 'admin',
            ]
        );
    }
}
