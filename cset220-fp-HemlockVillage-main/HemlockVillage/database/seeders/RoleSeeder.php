<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleSeeder::insertData("Admin", 1);
        RoleSeeder::insertData("Supervisor", 2);
        RoleSeeder::insertData("Doctor", 3);
        RoleSeeder::insertData("Caregiver", 4);
        RoleSeeder::insertData("Patient", 5);
        RoleSeeder::insertData("Family", 6);
    }

    private static function insertData($role, $accessLevel): void
    {
        Role::create([
            "role" => $role,
            "access_level" => $accessLevel
        ]);
    }
}
