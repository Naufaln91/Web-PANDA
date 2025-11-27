<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Whitelist;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin using environment variables (via config)
        $adminUsername = config('admin.username');
        $adminPassword = config('admin.password');

        User::updateOrCreate(
            ['username' => $adminUsername],
            [
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'email' => null, // Admin doesn't need email
            ]
        );

        // Add sample whitelisted emails
        Whitelist::firstOrCreate(['email' => 'guru@example.com'], ['role' => 'guru']);
        Whitelist::firstOrCreate(['email' => 'wali@example.com'], ['role' => 'wali_murid']);
        Whitelist::firstOrCreate(['email' => 'guru2@example.com'], ['role' => 'guru']);

        echo "✅ Admin created/updated: username={$adminUsername}, password={$adminPassword}\n";
        echo "✅ Sample whitelisted numbers added\n";
    }
}
