<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Appointment;
use App\Helpers\ModelHelper;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            PatientSeeder::class,
            AppointmentSeeder::class,
            PrescriptionStatusSeeder::class,
            MealSeeder::class,
            RosterSeeder::class,
        ]);
    }
}
