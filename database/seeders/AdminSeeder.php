<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        User::updateOrCreate(
            ['email' => 'admin@acservice.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Admin backup
        User::updateOrCreate(
            ['email' => 'backup@acservice.com'],
            [
                'name' => 'Admin Backup',
                'password' => Hash::make('backup123'),
            ]
        );
    }
}
