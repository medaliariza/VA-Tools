<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@vatools.local'],
            [
                'fullname' => 'VA Tools Admin',
                'password' => Hash::make('Admin@12345678!'),
                'role' => 'admin',
                'department' => 'Administration',
                'organization' => 'VA Tools',
                'email_verified_at' => now(),
            ]
        );
    }
}
