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
        ]);

        // Add sample whitelisted phone numbers
        Whitelist::create(['nomor_hp' => '081234567890']);
        Whitelist::create(['nomor_hp' => '082345678901']);
        Whitelist::create(['nomor_hp' => '083456789012']);

        echo "✅ Admin created: username=admin, password=admin123\n";
        echo "✅ Sample whitelisted numbers added\n";
    }
}
