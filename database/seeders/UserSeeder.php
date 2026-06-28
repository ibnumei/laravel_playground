<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk membuat user default admin (username: admin, password: admin123).
 */
class UserSeeder extends Seeder
{
    /**
     * Membuat satu user admin default jika belum ada di database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('admin123')]
        );
    }
}
