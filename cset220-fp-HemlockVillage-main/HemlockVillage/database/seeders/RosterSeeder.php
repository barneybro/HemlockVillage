<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Roster;
use App\Models\Employee;

class RosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't touch
        self::insertData("2024-11-02", 2, 3, 4, 5, 6, 7, 8);
        self::insertData("2024-11-03", 2, 3, 4, 5, 6, 7, 8);
        self::insertData("2024-11-06", 2, 3, 4, 5, 6, 7, 8);


        // Touchable - don't insert null values
        // the caregiver ids should be within these values 4, 7-20, 71, 77
        self::insertData("2024-12-03",  2, 3, 4, 5, 6, 7, 8);
        self::insertData("2024-12-04", Employee::getId(21), Employee::getId(25), Employee::getId(7), Employee::getId(8), Employee::getId(9), Employee::getId(4));
        self::insertData("2024-12-05", Employee::getId(22), Employee::getId(26), Employee::getId(4), Employee::getId(7), Employee::getId(11), Employee::getId(12));
        self::insertData("2024-12-06", Employee::getId(21), Employee::getId(3), Employee::getId(12), Employee::getId(8), Employee::getId(9), Employee::getId(4));
        self::insertData("2024-12-07", Employee::getId(22), Employee::getId(26), Employee::getId(10), Employee::getId(4), Employee::getId(7), Employee::getId(11));
        self::insertData("2024-12-08", Employee::getId(2), Employee::getId(3), Employee::getId(4), Employee::getId(10), Employee::getId(8), Employee::getId(7));
        self::insertData("2024-12-09", Employee::getId(21), Employee::getId(3), Employee::getId(4), Employee::getId(12), Employee::getId(9), Employee::getId(11));
        self::insertData("2024-12-10", Employee::getId(22), Employee::getId(26), Employee::getId(4), Employee::getId(7), Employee::getId(10), Employee::getId(12));
        self::insertData("2024-12-11", Employee::getId(21), Employee::getId(25), Employee::getId(9), Employee::getId(11), Employee::getId(4), Employee::getId(7));

        // Upcoming roster to match the appointments table
        self::insertData("2024-12-12", Employee::getId(22), Employee::getId(3), Employee::getId(4), Employee::getId(7), Employee::getId(8), Employee::getId(9));
        self::insertData("2024-12-13", Employee::getId(2), Employee::getId(3), Employee::getId(12), Employee::getId(9), Employee::getId(4), Employee::getId(10));
        self::insertData("2024-12-29", Employee::getId(2), Employee::getId(3), Employee::getId(4), Employee::getId(7), Employee::getId(8), Employee::getId(12));
        self::insertData("2024-12-30", Employee::getId(21), Employee::getId(3), Employee::getId(4), Employee::getId(10), Employee::getId(12), Employee::getId(7));
        self::insertData("2024-12-31", Employee::getId(22), Employee::getId(3), Employee::getId(4), Employee::getId(10), Employee::getId(11), Employee::getId(12));
        self::insertData("2024-12-20", Employee::getId(2), Employee::getId(25), Employee::getId(4), Employee::getId(7), Employee::getId(8), Employee::getId(10));
        self::insertData("2024-12-18", Employee::getId(21), Employee::getId(26), Employee::getId(9), Employee::getId(7), Employee::getId(10), Employee::getId(4));
        self::insertData("2024-12-19", Employee::getId(22), Employee::getId(25), Employee::getId(4), Employee::getId(7), Employee::getId(8), Employee::getId(12));
        self::insertData("2024-12-22", Employee::getId(2), Employee::getId(25), Employee::getId(9), Employee::getId(8), Employee::getId(4), Employee::getId(7));
        self::insertData("2024-12-21", Employee::getId(21), Employee::getId(26), Employee::getId(4), Employee::getId(7), Employee::getId(12), Employee::getId(8));
        self::insertData("2024-12-23", Employee::getId(22), Employee::getId(3), Employee::getId(4), Employee::getId(10), Employee::getId(11), Employee::getId(7));
        self::insertData("2024-12-25", Employee::getId(2), Employee::getId(3), Employee::getId(4), Employee::getId(8), Employee::getId(9), Employee::getId(7));
        self::insertData("2024-12-24", Employee::getId(21), Employee::getId(26), Employee::getId(4), Employee::getId(8), Employee::getId(7), Employee::getId(9));
        self::insertData("2024-12-27", Employee::getId(22), Employee::getId(3), Employee::getId(4), Employee::getId(8), Employee::getId(7), Employee::getId(12));
        self::insertData("2024-12-28", Employee::getId(2), Employee::getId(25), Employee::getId(9), Employee::getId(4), Employee::getId(7), Employee::getId(12));
        self::insertData("2024-01-01", Employee::getId(2), Employee::getId(3), Employee::getId(4), Employee::getId(9), Employee::getId(7), Employee::getId(12));
    }

    private static function insertData($dateAssigned, $supervisorID, $doctorID, $caregiverOneID, $caregiverTwoID, $caregiverThreeID, $caregiverFourID): void
    {
        Roster::create([
            "date_assigned" => $dateAssigned,
            "supervisor_id" => $supervisorID ?? null,
            "doctor_id" => $doctorID ?? null,
            "caregiver_one_id" => $caregiverOneID ?? null,
            "caregiver_two_id" => $caregiverTwoID ?? null,
            "caregiver_three_id" => $caregiverThreeID ?? null,
            "caregiver_four_id" => $caregiverFourID ?? null,
        ]);
    }
}
