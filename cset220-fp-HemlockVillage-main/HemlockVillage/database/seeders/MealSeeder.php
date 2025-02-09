<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Meal;
use App\Models\Patient;


class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't touch
        self::insertData(Patient::getId(5), "2024-11-01", "Missing", "Completed", "Completed");
        self::insertData(Patient::getId(5), "2024-11-03", "Missing", "Completed", "Completed");

        // Touchable
        self::insertData(Patient::getId(35), "2024-11-10", "Missing", "Completed", "Completed");
        self::insertData(Patient::getId(35), "2024-12-11", "Completed", "Completed", "Missing");
        self::insertData(Patient::getId(37), "2024-12-09", "Missing", "Completed", "Completed");
        self::insertData(Patient::getId(39), "2024-12-11", "Missing", "Missing", "Completed");
        self::insertData(Patient::getId(41), "2024-12-11", "Missing", "Completed", "Missing");
        self::insertData(Patient::getId(43), "2024-12-07", "Completed", "Missing", "Missing");
        self::insertData(Patient::getId(43), "2024-12-08", "Completed", "Completed", "Completed");


        // 2024-12-12
        self::insertData(Patient::getId(5), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(35), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(36), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(37), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(38), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(39), "2024-12-12", "Pending", "Pending", "Pending");
        self::insertData(Patient::getId(40), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(41), "2024-12-12", "Completed", "Pending", "Pending");
        self::insertData(Patient::getId(42), "2024-12-12", "Completed", "Pending", "Missing");
        self::insertData(Patient::getId(43), "2024-12-12", "Missing", "Pending", "Pending");
    }

    private static function insertData($patientID, $mealDate, $breakfast, $lunch, $dinner): void
    {
        Meal::create([
            "patient_id" => $patientID,
            "meal_date" => $mealDate,
            "breakfast" => $breakfast,
            "lunch" => $lunch,
            "dinner" => $dinner
        ]);
    }

}
