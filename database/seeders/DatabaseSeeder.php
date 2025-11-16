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
        // Create Admin using environment variables
        $adminUsername = env('ADMIN_USERNAME', 'admin');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        User::create([
            'username' => $adminUsername,
            'password' => Hash::make($adminPassword),
            'role' => 'admin',
            'email' => null, // Admin doesn't need email
        ]);

        // Add sample whitelisted emails
        Whitelist::create(['email' => 'guru@example.com', 'role' => 'guru']);
        Whitelist::create(['email' => 'wali@example.com', 'role' => 'wali_murid']);
        Whitelist::create(['email' => 'guru2@example.com', 'role' => 'guru']);

        echo "✅ Admin created: username={$adminUsername}, password={$adminPassword}\n";
        echo "✅ Sample whitelisted numbers added\n";
    }
}
