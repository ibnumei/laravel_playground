<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Menjalankan semua seeder database: data RBAC (Role, User, Menu, SubMenu, RoleSubMenu).
     */
    public function run(): void
    {
        $this->call(RbacSeeder::class);
    }
}
