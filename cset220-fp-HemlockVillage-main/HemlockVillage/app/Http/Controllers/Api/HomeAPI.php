<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllerHelper;
use App\Helpers\UpdaterHelper;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use App\Models\Employee;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PrescriptionStatus;
use App\Models\Roster;

use Datetime;

use App\Helpers\ValidationHelper;

class HomeAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public static function indexDoctor(string $userId)
    {
        // get employee id of user
        $doctorId = Employee::getId($userId);

        // Get all past appointments for the doctor
        $appointments = Appointment::with([
            "patient",
            "patient.user"
        ])
        ->where("doctor_id", $doctorId)
        ->where("appointment_date", "<=", Carbon::today())
        ->where("status", "<>", "Pending")
        ->orderBy("appointment_date", "asc")
        ->orderBy("patient_id", "asc")
        ->get();

        // return response()->json($appointments);

        $data = $appointments->map(function ($a)
        {
            $user = $a->patient->user;

            return [
                "patient_id" => $a->patient->id,
                "patient_name" => "{$user->first_name} {$user->last_name}",
                "appointment_date" => $a->appointment_date,
                "status" => $a->status,
                "comment" => $a->comment,
                "prescription" => [
                    "morning" => $a->morning ?? null,
                    "afternoon" => $a->afternoon ?? null,
                    "night" => $a->night ?? null,
                ]
            ];
        });

        return $data;
    }

    public static function showDoctor($doctorId, $date)
    {
        /**
         * Validation
         */
        // Doctor does not exist in appointments table (no appointments)
        if (!Appointment::where("doctor_id", $doctorId)->first())
            return [];

        // Invalid date format
        if (!strtotime($date))
            abort(400, "Invalid date format");

        $currentDate = new DateTime();
        $inputDate = DateTime::createFromFormat("Y-m-d", $date);

        // Check if DateTime obj can be created successfullly and the formatted date matches the date passed in
        if (!$inputDate || !$inputDate->format("Y-m-d") === $date)
            abort(400, "Invalid date format");

        // Set the time component to 00:00:00 so time does not affect date comparision
        $currentDate->setTime(0, 0);
        $inputDate->setTime(0, 0);

        // Check if inputDate is before currentDate
        if ($inputDate < $currentDate)
            abort(400, "Date cannot be before the today {$currentDate->format('Y-m-d')}");

        return ControllerHelper::getDoctorPatientsUpToDate($doctorId, $date);
    }

    public static function showPatient($patientId, $date)
    {
        $patient = Patient::find($patientId);

        if (!$patient) return response()->json([ "error" => "Patient could not be found" ], 404);

        ValidationHelper::validateDateFormat($date);

        $appointment = Appointment::with([
            "doctor.user"
        ])
        ->where("patient_id", "=", $patientId)
        ->whereDate("appointment_date", $date)
        ->first();

        $patientGroup = $patient->group_num ?? null;

        // Find the correct caregiver for patient
        $caregiverString = "";
        switch ($patientGroup)
        {
            case "1":
                $caregiverString = "one";
                break;
            case "2":
                $caregiverString = "two";
                break;
            case "3":
                $caregiverString = "three";
                break;
            case "4":
                $caregiverString = "four";
                break;
            default:
                $caregiverString = null;
        }

        // Dynamically determine which caregiver number is associated with patient group num
        $caregiverString = "caregiver_{$caregiverString}_id" ?? null;

        // Find caregiver info from users table
        $caregiver = DB::table("rosters")
            ->join("employees", "rosters.{$caregiverString}", "=", "employees.id")
            ->join("users", "employees.user_id", "=", "users.id")
            ->where("rosters.date_assigned", "=", $date)
            ->select("users.first_name", "users.last_name")
            ->first();

        /**
         *  Find prescription status of a patient for a date
         *  Add `::with(["appointment.patient"])` to get the appointment details
         */
        $prescriptionStatus = PrescriptionStatus::where("prescription_date", $date)
        ->whereHas("appointment", function ($query) use ($patientId)
        {
            $query->where("patient_id", "=", $patientId);
        })->first();

        // Find meal status of a patient for a date
        $mealStatus = DB::table("meals")->where([
            [ "patient_id", "=", $patientId ],
            [ "meal_date", "=", $date ]
        ])->first();

        $response = UpdaterHelper::updateMeal($patientId, $date);
        $jsonDecoded = json_decode($response->getContent(), true);

        $mealStatus = $jsonDecoded["meal"];

        return [
            "patient_id" => $patient->id ?? null,
            "patient_name" => $patient->user ? "{$patient->user->first_name} {$patient->user->last_name}" : null,
            "date" => Carbon::parse($date)->toDateString() ?? "",
            "doctor_name" => $appointment ? "{$appointment->doctor->user->first_name} {$appointment->doctor->user->last_name}" : null, // Not null if there is an appointment that date
            "appointment_status" => $appointment ? $appointment->status : null, // Not null if there is an appointment that date
            "caregiver_name" => $caregiver ? "{$caregiver->first_name} {$caregiver->last_name}" : null,
            "prescription_status" => [
                "morning" => $prescriptionStatus->morning ?? null,
                "afternoon" => $prescriptionStatus->afternoon ?? null,
                "night" => $prescriptionStatus->night ?? null,
                ],
            "meal_status" => [
                "breakfast" => $mealStatus->breakfast ?? $mealStatus["breakfast"] ?? null,
                "lunch" => $mealStatus->lunch ?? $mealStatus["breakfast"] ?? null,
                "dinner" => $mealStatus->dinner ?? $mealStatus["breakfast"] ?? null,
                ]
        ];
    }

    public static function showCaregiver($caregiverId, $date)
    {
        /**
         * Get roster
         */
        // To test, set date 2024-11-03
        $roster = Roster::whereDate("date_assigned", $date)->first();

        // No roster created
        // --- Comment out for testing bypass
        if (!$roster)
        {
            return response()->json([
                "message" => "No roster has been created for today { " . Carbon::today()->format("Y-m-d") . " }"
            ], 204); // 204 - No content
        }

        /**
         * Find group num that caregiver is assigned for
         */
        $groupNum = null;

        // Find which column the caregiver is under in the roster
        // --- Comment out for testing bypass of no roster
        foreach ($roster->getAttributes() as $col => $val)
        {
            if ($val === $caregiverId) $groupNum = $col;
        }

        // Get group num
        $groupNum = ControllerHelper::convertRosterCaregiverToNumeric($groupNum);

        // Caregiver is not on the roster
        // --- Comment out for testing bypass
        if (!$groupNum)
        {
            return response()->json([
                "message" => "You are not assigned on the roster today { " . Carbon::today()->format("Y-m-d") . " }"
            ], 204); // 204 - No content
        }

        /**
         * Find all the patients under this group num
         */
        // --- Hard-code group_num value as 1 to test if needed to bypass
        $patients = Patient::join("users", "patients.user_id", "users.id")->where("group_num", $groupNum)->get();

        foreach ($patients as $p)
        {
            // To test, set date to 2024-11-03
            $PrescriptionStatusAppointment = ControllerHelper::getPatientPrescriptionStatusAppointmentByDate(Patient::getId($p->id), $date);

            $appointment = $PrescriptionStatusAppointment->appointment ?? null; // Appointment and doctor info

            // To test, set date to 2024-11-01
            $meal = ControllerHelper::getPatientMealStatusByDate(Patient::getId($p->id), $date);

            $data[] = [
                "patient_id" => Patient::getId($p->id),
                "prescription_status_id" => $PrescriptionStatusAppointment->id ?? null,
                "meal_id" => $meal["meal_id"],
                "patient_name" => "{$p->first_name} {$p->last_name}",
                "appointment_status" => $appointment->status ?? null,
                "prescriptions" => ControllerHelper::getPatientPrescriptionByDate($PrescriptionStatusAppointment),
                "prescription_status" => ControllerHelper::getPatientPrescriptionStatusByDate($PrescriptionStatusAppointment),
                "meal_status" => $meal["status_data"]
            ];
        }

        return response()->json([
            "data" => $data,
            "groupNum" => $groupNum
        ]);
    }

    public static function showFamily($patientId, $familyCode, $date)
    {
        /**
         * Validation of $patientId and $familyCode
         */
        $validatedPatient = DB::table('patients')
            ->where('id', $patientId)
            ->where('family_code', $familyCode)
            ->exists();

        // Incorrect combination of patient id and family code
        if (!$validatedPatient)
        {
            // return redirect()->back()->withErrors([ "Could not find a patient with id { $patientId } and family code { $familyCode }"]);
            return response()->json([
                "success" => false,
                "message" => "Could not find patient",
                "errors" => [ "Could not find a patient with id { $patientId } and family code { $familyCode }" ]
            ], 400);
        }

        /**
         * Data retrieval
         */
        $prescriptionStatusAppointment = ControllerHelper::getPatientPrescriptionStatusAppointmentByDate($patientId, $date);

        $appointment = $prescriptionStatusAppointment->appointment ?? null;

        $caregiver = ControllerHelper::getPatientCaregiverByDate($patientId, $date);

        return response()->json([
            "success" => true,
            "message" => "Data has been retrieved",
            "data" => [
                "doctor_name" => $appointment ? "{$appointment->doctor->user->first_name} {$appointment->doctor->user->last_name}" : null,
                "appointment_status" => $appointment ? $appointment->status : null,
                "caregiver_name" => $caregiver["caregiver_name"],
                "prescriptions" => ControllerHelper::getPatientPrescriptionByDate($prescriptionStatusAppointment),
                "prescription_status" => ControllerHelper::getPatientPrescriptionStatusByDate($prescriptionStatusAppointment),
                "meal_status" => ControllerHelper::getPatientMealStatusByDate($patientId, $date)["status_data"]
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
