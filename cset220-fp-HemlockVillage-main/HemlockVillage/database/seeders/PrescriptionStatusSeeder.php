<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PrescriptionStatus;

class PrescriptionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't touch
        self::insertData(1, "2024-11-03", "Completed", "Completed", null);
        self::insertData(2, "2024-12-04", "Completed", null, null);

        // Touchable
        // self::insertData(3, "2025-01-01", "Missing", "Completed", "Completed");
        // self::insertData(4, "2024-12-10", "Completed", "Completed","Completed");
        // self::insertData(5, "2024-12-15", "Missing", "Completed", "Pending");
        // self::insertData(6, "2024-12-12", "Completed", "Completed", "Completed");
        // self::insertData(7, "2024-12-10", "Completed", "Completed", "Completed");
        // self::insertData(8, "2024-12-11", "Pending", "Missing", "Completed");
        // self::insertData(9, "2024-12-12", "Completed", "Completed", "Pending");
        // self::insertData(10, "2024-12-13", "Missing", "Completed", "Completed");
        // self::insertData(11, "2024-12-09", "Completed", "Missing", "Pending");

        self::insertData(4, '2024-12-12', "Pending", "Pending", null);
        self::insertData(17, '2024-12-12', "Pending", "Pending", null);
        self::insertData(18, '2024-12-12', "Pending", "Pending", "Pending");
        self::insertData(19, '2024-12-12', "Pending", "Pending", "Pending");
        self::insertData(20, '2024-12-12', "Pending", "Pending", "Pending");
        self::insertData(21, '2024-12-12', "Pending", null, null);
        self::insertData(22, '2024-12-12', "Pending", "Pending", null);
        self::insertData(23, '2024-12-12', "Pending", "Pending", "Pending");
    }

    private static function insertData($appointmentId, $date, $morning, $afternoon, $night): void
    {
        PrescriptionStatus::create([
            "appointment_id" => $appointmentId,
            "prescription_date" => $date,
            "morning" => $morning,
            "afternoon" => $afternoon,
            "night" => $night,
        ]);
    }
}
