<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't touch
        self::insertData(Str::random(16), 5, Str::random(16), "John Doe", '111-111-1111', "Brother", "2024-11-01", "1", "2024-11-02", "2024-12-01", 15);

        // Unapproved Patients
        self::insertData(Str::random(16), 82, Str::random(16), "Mary Janes", '213-231-9786', "Brother", null, null, null, null, 0);
        self::insertData(Str::random(16), 84, Str::random(16), "Mike Bender", '152-348-3480', "Brother", null, null, null, null, 0);

        // Touchable -- all these will need prescriptionUpdatedDate added
        self::insertData(Str::random(16), 35, Str::random(16), "Jane Smith", '111-222-3333', "Sister", "2024-09-01", 2, "2024-09-05", "2024-12-01", 20);
        self::insertData(Str::random(16), 36, Str::random(16), "Emily Johnson", '111-333-4444', "Mother", "2024-08-15", 3, "2024-08-20", "2024-12-02", 25);
        self::insertData(Str::random(16), 37, Str::random(16), "Michael Williams", '111-444-5555', "Father", "2024-06-10", 4, "2024-06-12", "2024-12-01", 30);
        self::insertData(Str::random(16), 38, Str::random(16), "David Brown", '111-555-6666', "Brother", "2024-07-05", 1, "2024-07-07", "2024-12-03", 35);
        self::insertData(Str::random(16), 39, Str::random(16), "Linda Davis", '111-666-7777', "Wife", "2024-05-20", 2, "2024-05-22", "2024-12-01", 40);
        self::insertData(Str::random(16), 40, Str::random(16), "James Miller", '111-777-8888', "Son", "2024-03-15", 3, "2024-03-18", "2024-12-02", 45);
        self::insertData(Str::random(16), 41, Str::random(16), "Sophia Wilson", '111-888-9999', "Daughter", "2024-04-01", 4, "2024-04-05", "2024-12-01", 50);
        self::insertData(Str::random(16), 42, Str::random(16), "Benjamin Moore", '111-999-0000', "Husband", "2024-02-10", 1, "2024-02-12", "2024-12-03", 55);
        self::insertData(Str::random(16), 43, Str::random(16), "Isabella Taylor", '111-000-1111', "Mother", "2024-01-25", 2, "2024-01-28", "2024-12-02", 60);

    }
    private static function insertData($id, $userID, $familyCode, $econtactName, $econtactPhone, $relation, $admissionDate, $groupNum, $dailyUpdatedDate, $prescriptionUpdatedDate, $bill): void
    {
        Patient::create([
            "id" => $id,
            "user_id" => $userID,
            "family_code" => $familyCode,
            "econtact_name" => $econtactName,
            "econtact_phone" => $econtactPhone,
            "econtact_relation" => $relation,
            "admission_date" => $admissionDate ?? null,
            "group_num" => $groupNum ?? null,
            "daily_updated_date" => $dailyUpdatedDate ?? date("Y-m-d"),
            "prescription_updated_date" => $prescriptionUpdatedDate ?? date("Y-m-d"),
            "bill" => $bill ?? 0
        ]);
    }
}
