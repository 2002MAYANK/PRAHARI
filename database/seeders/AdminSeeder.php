<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed a default admin user if none exists.
     */
    public function run(): void
    {
        if (User::where('role', 'admin')->exists()) {
            $this->command->info('Admin user already exists — skipping.');
            return;
        }

        User::create([
            'name'     => env('ADMIN_NAME', 'Admin'),
            'email'    => env('ADMIN_EMAIL', 'admin@prahari.com'),
            'phone'    => env('ADMIN_PHONE', '9999999999'),
            'password' => env('ADMIN_PASSWORD', 'Prahari@2026'),
            'role'     => 'admin',
        ]);

        $this->command->info('✅ Default admin user created.');
    }
}
