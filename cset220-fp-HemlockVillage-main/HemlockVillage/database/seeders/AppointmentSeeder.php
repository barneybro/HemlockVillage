<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;

use DateTime;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't touch
        self::insertData(Patient::getId(5), "2024-11-01", "2024-11-02", 3, "Completed", "low on iron", "take 6mg iron supplement", "take 6mg iron supplement", null); // 1
        self::insertData(Patient::getId(5), "2024-11-02", "2024-12-03", 3, "Completed", "slightly low on iron", "take 6mg iron supplement", null, null); // 2
        self::insertData(Patient::getId(5), "2024-11-04", "2025-01-01", 3, "Pending"); // 3

        self::insertData(Patient::getId(5), '2024-11-05', '2024-11-06', 3, 'Completed', 'much better on iron level', "take 1mg iron supplement", null, null); // 4
        self::insertData(Patient::getId(5), '2024-11-05', '2024-12-25', 3, 'Pending', 'test ordering of future date. this comment in reality should not be here, but just here to inform its purpose...', null, null, null, null, null); // 5

        // Some missed appointments
        self::insertData(Patient::getId(5), "2024-12-10", "2024-12-11", Employee::getId(25), "Missing", null, null, null, null); // 6
        self::insertData(Patient::getId(37), '2024-12-01', '2024-12-05', Employee::getId(26), "Missing", null, null, null, null); // 7
        self::insertData(Patient::getId(38), '2024-12-03', '2024-12-05', Employee::getId(26), "Missing", null, null, null, null); // 8
        self::insertData(Patient::getId(39), '2024-12-02', '2024-12-05', Employee::getId(25), "Missing", null, null, null, null); // 9
        self::insertData(Patient::getId(41), '2024-12-01', '2024-12-02', Employee::getId(3), "Missing", null, null, null, null); // 10
        self::insertData(Patient::getId(43), '2024-12-02', '2024-12-05', Employee::getId(26), "Missing", null, null, null, null); // 11
        self::insertData(Patient::getId(43), '2024-12-01', '2024-12-03', Employee::getId(25), "Missing", null, null, null, null); // 12
        self::insertData(Patient::getId(39), '2024-12-08', '2024-12-09', Employee::getId(3), "Missing", null, null, null, null); // 13
        self::insertData(Patient::getId(35), '2024-12-03', '2024-12-06', Employee::getId(3), "Missing", null, null, null, null); // 14
        self::insertData(Patient::getId(37), '2024-12-06', '2024-12-09', Employee::getId(3), "Missing", null, null, null, null); // 15
        self::insertData(Patient::getId(35), '2024-12-04', '2024-12-05', Employee::getId(26), "Missing", null, null, null, null); // 16

        // Some completed appointments
        self::insertData(Patient::getId(37), '2024-12-01', '2024-12-10', Employee::getId(26), "Completed", "Back pain", "apply heat therapy", "drink more water", null); // 17
        self::insertData(Patient::getId(38), '2024-12-05', '2024-12-10', Employee::getId(26), "Completed", "High blood pressure", "reduce salt intake", "Increase exercise", null); // 18
        self::insertData(Patient::getId(39), '2024-12-01', '2024-12-12', Employee::getId(3), "Completed", "Routine checkup - healthy diet", null, null, null); // 19
        self::insertData(Patient::getId(40), '2024-12-02', '2024-12-10', Employee::getId(26), "Completed", "Low vitamin B12", "take B12 supplement", "Increase sunlight exposure", null); // 20
        self::insertData(Patient::getId(41), '2024-12-01', '2024-12-11', Employee::getId(25), "Completed", "Allergy test", null, "take 1 tsp of drug", null); // 21
        self::insertData(Patient::getId(42), '2024-12-02', '2024-12-12', Employee::getId(3), "Completed", "Mild cold", "drink warm fluids", "Take mucinx 12hr tablet for congestion", null); // 22
        self::insertData(Patient::getId(43), '2024-12-10', '2024-12-11', Employee::getId(25), "Completed", "Follow-up on vaccination", "monitor side effects", "drink glass of water", null); // 23

        // Patients dont' exist
        // self::insertData(Patient::getId(47), '2024-12-01', '2024-12-09', Employee::getId(3), "Completed", "General fatigue", "increase rest", "Hydrate regularly", 'afternoon'); // 24
        // self::insertData(Patient::getId(48), '2024-12-05', '2024-12-11', Employee::getId(25), "Completed", "Checkup after surgery", "monitor recovery", "Follow post-surgery guidelines", 'night'); // 25
        // self::insertData(Patient::getId(35), '2024-12-05', '2024-12-09', Employee::getId(3), "Completed", "Minor injury", "apply antiseptic", "Avoid heavy lifting", null); // 26

        // Future appointments
        self::insertData(Patient::getId(5), "2024-12-11", "2024-12-12", Employee::getId(3), "Pending", null, null, null, null); // 27
        self::insertData(Patient::getId(5), "2024-12-13", "2024-12-13", Employee::getId(3), "Pending", null, null, null, null); // 28
        self::insertData(Patient::getId(37), '2024-12-25', '2024-12-29', Employee::getId(3), "Pending", null, null, null, null); // 29
        self::insertData(Patient::getId(38), '2024-12-26', '2024-12-30', Employee::getId(3), "Pending", null, null, null, null); // 30
        self::insertData(Patient::getId(39), '2024-12-27', '2024-12-31', Employee::getId(3), "Pending", null, null, null, null); // 31
        self::insertData(Patient::getId(37), '2024-12-15', '2024-12-20', Employee::getId(25), "Pending", null, null, null, null); // 32
        self::insertData(Patient::getId(38), '2024-12-16', '2024-12-18', Employee::getId(26), "Pending", null, null, null, null); // 33
        self::insertData(Patient::getId(39), '2024-12-17', '2024-12-19', Employee::getId(25), "Pending", null, null, null, null); // 34 (Updated to Doctor ID 25)
        self::insertData(Patient::getId(41), '2024-12-18', '2024-12-22', Employee::getId(25), "Pending", null, null, null, null); // 35
        self::insertData(Patient::getId(43), '2024-12-19', '2024-12-21', Employee::getId(26), "Pending", null, null, null, null); // 36
        self::insertData(Patient::getId(40), '2024-12-20', '2024-12-23', Employee::getId(3), "Pending", null, null, null, null); // 37
        self::insertData(Patient::getId(42), '2024-12-21', '2024-12-25', Employee::getId(3), "Pending", null, null, null, null); // 38
        self::insertData(Patient::getId(43), '2024-12-22', '2024-12-24', Employee::getId(26), "Pending", null, null, null, null); // 39
        self::insertData(Patient::getId(37), '2024-12-23', '2024-12-27', Employee::getId(3), "Pending", null, null, null, null); // 40
        self::insertData(Patient::getId(35), '2024-12-24', '2024-12-28', Employee::getId(25), "Pending", null, null, null, null); // 41

    }

    private static function insertData($patientID, $dateScheduled, $appointmentDate, $doctorID, $status, $comment = null, $morning = null, $afternoon = null, $night = null): void
    {
        Appointment::create([
            "patient_id" => $patientID,
            "date_scheduled" => $dateScheduled,
            "appointment_date" => $appointmentDate,
            "doctor_id" => $doctorID,
            "status" => $status,
            "comment" => $comment ?? null,
            "morning" => $morning ?? null,
            "afternoon" => $afternoon ?? null,
            "night" => $night ?? null
        ]);
    }

}
