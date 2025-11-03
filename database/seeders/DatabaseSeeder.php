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
        // Create Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email' => null, // Admin doesn't need email
        ]);

        // Add sample whitelisted emails
        Whitelist::create(['email' => 'guru@example.com', 'role' => 'guru']);
        Whitelist::create(['email' => 'wali@example.com', 'role' => 'wali_murid']);
        Whitelist::create(['email' => 'guru2@example.com', 'role' => 'guru']);

        echo "✅ Admin created: username=admin, password=admin123\n";
        echo "✅ Sample whitelisted numbers added\n";
    }
}
