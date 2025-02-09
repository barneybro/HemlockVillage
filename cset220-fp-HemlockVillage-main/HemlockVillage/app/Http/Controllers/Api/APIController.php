<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Helpers\ValidationHelper;
use App\Helpers\ControllerHelper;
use App\Helpers\UpdaterHelper;
use App\Models\Patient;
use App\Models\Roster;
use App\Models\Appointment;
use App\Models\PrescriptionStatus;
use App\Models\Meal;

use Carbon\Carbon;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public static function indexRosterCreation()
    {
        return [
            "supervisors" => ControllerHelper::getEmployeeForRosterCreation("Supervisor"),
            "doctors" => ControllerHelper::getEmployeeForRosterCreation("Doctor"),
            "caregivers" => ControllerHelper::getEmployeeForRosterCreation("Caregiver")
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public static function storeRosterForm(Request $request)
    {
        /**
         * Validation
         */
        $validatedData = Validator::make($request->all(), [
            "date" => [ "required", "date", "unique:rosters,date_assigned", "after_or_equal:today" ],
            "supervisor" => [ "required", "exists:employees,id" ],
            "doctor" => [ "required", "exists:employees,id" ],
            "caregivers" => [ "required", "array", "size:4" ],
            "caregivers.*" => [ "exists:employees,id", "distinct" ], // Applies to each caregiver
        ], ValidationHelper::$roster);

        if ($validatedData->fails())
        {
            return response()->json([
                "success" => false,
                "message" => "Roster could not be created",
                "errors" => $validatedData->errors()
            ], 400);
        }

        /**
         * Creation
         */
        $roster = Roster::create([
            "date_assigned" => $request->get("date"),
            "supervisor_id" => $request->get("supervisor"),
            "doctor_id" => $request->get("doctor"),
            "caregiver_one_id" => $request->get("caregivers")[0],
            "caregiver_two_id" => $request->get("caregivers")[1],
            "caregiver_three_id" => $request->get("caregivers")[2],
            "caregiver_four_id" => $request->get("caregivers")[3]
        ]);

        // Success
        return response()->json([
            "success" => true,
            "message" => "Roster for date { {$request->get('date')} } has been created",
            "roster" => $roster
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public static function getReportNew($date, $status)
    {
        ValidationHelper::validateDateFormat($date);

        // $status = "Missing";

        $data = Patient::with([
            "user" => fn($q) => $q->select("id", "first_name", "last_name"),

            "appointments" => fn($q) => $q->select("id", "appointment_date", "patient_id", "doctor_id", "status", "morning", "afternoon", "night", "appointment_date")
                ->whereDate('appointment_date', $date), // Need the date to filter correctly to the appointment date. The problem is that you lose the id to connect to prescription status if prescription status has "Missing" but Appointment is not "Missing", so you won't get the prescription data and status data

            "appointments.doctor" => fn($q) => $q->select("id", "user_id"),
            "appointments.doctor.user" => fn($q) => $q->select("id", "first_name", "last_name"),

            "appointments.prescriptions" => fn($q) => $q->select("id", "appointment_id", "prescription_date", "morning", "afternoon", "night")
                ->whereDate('prescription_date', $date),

            "meals" => fn($q) => $q->select("id", "patient_id", "meal_date", "breakfast", "lunch", "dinner", "meal_date")
                ->whereDate('meal_date', $date)
        ])
        ->whereHas("appointments", function ($q) use ($date, $status)
        {
            $q->whereIn("status", $status)
                ->whereDate("appointment_date", $date);
        })
        ->orWhereHas("meals", function ($q) use ($date, $status)
        {
            $q->whereDate("meal_date", $date)
                ->where( function ($q) use ($status)
                {
                    $q->whereIn("breakfast", $status)
                        ->orWhereIn("lunch", $status)
                        ->orWhereIn("dinner", $status);
                });
        })
        ->orWhereHas("appointments.prescriptions", function ($q) use ($date, $status)
        {
            $q->whereDate("prescription_date", $date)
                ->where( function ($q) use ($status)
                {
                    $q->whereIn("morning", $status)
                        ->orWhereIn("afternoon", $status)
                        ->orWhereIn("night", $status);
                });
        })
        ->get();

        // return $data;

        // how to map with LengthAwarePaginator...

        // Mapping the paginated results
        return $data->map(function ($d) use ($date)
        {
            $patientUser = $d->user ?? null;
            $currentAppointment = ControllerHelper::getPatientAppointmentByDate($d->id, $date);

            /**
             * Get prescriptions for date
             */
            // Get all appointment_ids for that date and find the one in appointments table that matches the patient
            $appointmentIds = DB::table("prescription_statuses")->where("prescription_date", $date)->pluck("appointment_id");
            $appointment = DB::table("appointments")->where("patient_id", $d->id)->whereIn("id", $appointmentIds)->first();

            return [
                "date" => $date,
                "patient_name" => $patientUser ? "{$patientUser->first_name} {$patientUser->last_name}" : null,
                "doctor_name" => $currentAppointment["doctor_name"] ?? null,
                "appointment_status" => $currentAppointment["status"] ?? null,
                "caregiver_name" => ControllerHelper::getPatientCaregiverByDate($d->id, $date)["caregiver_name"],
                "prescriptions" => [
                    "morning" => $appointment->morning ?? null,
                    "afternoon" => $appointment->afternoon ?? null,
                    "night" => $appointment->night ?? null,
                ],
                "prescription_status" => ControllerHelper::getSimplePatientPrescriptionStatusByDate($appointment->id ?? null, $date),
                "meal_status" => ControllerHelper::getPatientMealStatusByDate($d->id, $date)["status_data"]
            ];
        });
    }

    public static function getReport($date)
    {
        ValidationHelper::validateDateFormat($date);

        // Status to search for
        $status = "Missing";

        // Eager load the data
        $data =  Patient::with([
            "user" => fn($q) => $q->select("id", "first_name", "last_name") ,
            "appointments" => fn($q) => $q->select("id", "patient_id", "doctor_id", "status", "morning", "afternoon", "night"),
            "appointments.doctor" => fn($q) => $q->select("id", "user_id"),
            "appointments.doctor.user" => fn($q) => $q->select("id", "first_name", "last_name"),
            "appointments.prescriptions" => fn($q) => $q->select("id", "appointment_id", "morning", "afternoon", "night"),
            "meals" => fn($q) => $q->select("id", "patient_id", "breakfast", "lunch", "dinner")
        ])
        ->whereHas("appointments", function ($q) use ($date, $status)
        {
            $q->where("status", $status)
                ->whereDate("appointment_date", $date);
        })
        ->orWhereHas("meals", function ($q) use ($date, $status)
        {
            $q->whereDate("meal_date", $date)
                ->where( function ($q) use ($status)
                {
                    $q->where("breakfast", $status)
                        ->orWhere("lunch", $status)
                        ->orWhere("dinner", $status);
                });
        })
        ->orWhereHas("appointments.prescriptions", function ($q) use ($date, $status)
        {
            $q->whereDate("prescription_date", $date)
                ->where( function ($q) use ($status)
                {
                    $q->where("morning", $status)
                        ->orWhere("afternoon", $status)
                        ->orWhere("night", $status);
                });
        })
        ->get();

        return $data->map( function ($d) use ($date)
        {
            $patient = $d->user ?? null;
            $doctor = $d->appointments->first()->doctor->user ?? null;
            $appointment = $d->appointments->first() ?? null;
            $prescriptionStatus = $appointment && $appointment->prescriptions ? $appointment->prescriptions->first() : null;
            $meal = $d->meals->first() ?? null;

            return [
                "patient_name" => $patient ? "{$patient->first_name} {$patient->last_name}" : null,
                "doctor_name" => $doctor ? "{$doctor->first_name} {$doctor->last_name}" : null,
                "appointment_status" => $appointment ? $appointment->status : null,
                "caregiver_name" => ControllerHelper::getPatientCaregiverByDate($d->id, $date)["caregiver_name"],
                "prescriptions" => [
                    "morning" => $appointment ? $appointment->morning : null,
                    "afternoon" => $appointment ? $appointment->afternoon : null,
                    "night" => $appointment ? $appointment->night : null,
                    ],
                "prescription_status" => [
                    "morning" => $prescriptionStatus ? $prescriptionStatus->morning : null,
                    "afternoon" => $prescriptionStatus ? $prescriptionStatus->afternoon : null,
                    "night" => $prescriptionStatus ? $prescriptionStatus->night : null,
                    ],
                "meal_status" => [
                    "breakfast" => $meal ? $meal->breakfast : null,
                    "lunch" => $meal ? $meal->lunch : null,
                    "dinner" => $meal ? $meal->dinner : null,
                    ]
            ];
        });
    }

    public static function showRoster($date)
    {
        /**
         * Validation
         */
        ValidationHelper::validateDateFormat($date);

        /**
         * Data retrieval
         */
        $roster = Roster::with([
            "supervisor" => fn($q) => $q->select('id', 'user_id'),
            "supervisor.user" => fn($q) => $q->select('id', 'first_name', 'last_name'),

            "doctor" => fn($q) => $q->select('id', 'user_id'),
            "doctor.user" => fn($q) => $q->select('id', 'first_name', 'last_name'),

            "caregiverOne" => fn($q) => $q->select('id', 'user_id'),
            "caregiverOne.user" => fn($q) => $q->select('id', 'first_name', 'last_name'),

            "caregiverTwo" => fn($q) => $q->select('id', 'user_id'),
            "caregiverTwo.user" => fn($q) => $q->select('id', 'first_name', 'last_name'),

            "caregiverThree" => fn($q) => $q->select('id', 'user_id'),
            "caregiverThree.user" => fn($q) => $q->select('id', 'first_name', 'last_name'),

            "caregiverFour" => fn($q) => $q->select('id', 'user_id'),
            "caregiverFour.user" => fn($q) => $q->select('id', 'first_name', 'last_name'),
        ])
        ->whereDate("date_assigned", $date)
        ->select('id', 'date_assigned', 'supervisor_id', 'doctor_id', 'caregiver_one_id', 'caregiver_two_id', 'caregiver_three_id', 'caregiver_four_id')
        ->first();

        // No roster
        if (!$roster) {
            return response()->json([
                "message" => "No roster found for date $date.",
                "data" => [],
            ], 404);
        }

        $data = [
            "roster_id" => $roster->id,
            "date" => $roster->date_assigned,

            "supervisor_id" => $roster->supervisor_id,
            "supervisor_name" => $roster->supervisor ? "{$roster->supervisor->user->first_name} {$roster->supervisor->user->last_name}" : null,

            "doctor_id" => $roster->doctor_id,
            "doctor_name" => $roster->doctor ? "{$roster->doctor->user->first_name} {$roster->doctor->user->last_name}" : null,

            "caregivers" => [
                "caregiver_one_id" => $roster->caregiver_one_id,
                "caregiver_one_name" => $roster->caregiverOne ? "{$roster->caregiverOne->user->first_name} {$roster->caregiverOne->user->last_name}" : null,

                "caregiver_two_id" => $roster->caregiver_two_id,
                "caregiver_two_name" => $roster->caregiverTwo ? "{$roster->caregiverTwo->user->first_name} {$roster->caregiverTwo->user->last_name}" : null,

                "caregiver_three_id" => $roster->caregiver_three_id,
                "caregiver_three_name" => $roster->caregiverThree ? "{$roster->caregiverThree->user->first_name} {$roster->caregiverThree->user->last_name}" : null,

                "caregiver_four_id" => $roster->caregiver_four_id,
                "caregiver_four_name" => $roster->caregiverFour ? "{$roster->caregiverFour->user->first_name} {$roster->caregiverFour->user->last_name}" : null,
            ]
        ];

        return response()->json([
            "data" => $data
        ], 200);
    }

    public static function showPayment($patientId)
    {
        /**
         * Validation
         */
        $patient = Patient::find($patientId);

        // Fails
        if (!$patient)
        {
            return response()->json([
                "patientId" => $patientId,
                "error" => "Patient with id { {$patientId} } does not exist"
            ], 404);
        }

        /**
         *  Success
         */
        // Add new charges if needed
        UpdaterHelper::addDailyCharge($patientId);
        UpdaterHelper::addMonthlyPrescriptionCharge($patientId);

        // Very important. It gets the most up-to-date data from the database
        // Otherwise the bill will not update on client-side for the current request
        $patient->refresh();

        return response()->json([
            "patientId" => $patientId,
            "bill" => $patient->bill
        ], 200);
    }

    public static function showDoctorPatient($doctorId, $patientId, $date)
    {
        /**
         * Validation
         */
        ValidationHelper::validateDateFormat($date);

        $patient = Patient::find($patientId);

        // Patient does not exist
        if (!$patient)
            abort("Patient with id { {$patientId} } not found");

        /**
         * Data retrieval
         */
        $appointments = DB::table("appointments")
            ->where("patient_id", $patientId)
            ->where(function($query) use ($date)
            {
                $query->whereDate("appointment_date", "<", $date) // Include appointments before the given date
                    ->orWhere(function($query) use ($date) // Include completed appointments for the given date
                    {
                        $query->whereDate("appointment_date", $date)
                            ->whereIn("status", ["Completed", "Missing"]);
                    });
            })
            ->where("doctor_id", $doctorId)
            ->orderBy("appointment_date", "desc")
            ->select("id", "patient_id", "appointment_date", "status", "comment", "morning", "afternoon", "night")
            ->paginate(1);

        $appointmentsData = $appointments->items();  // Actual appointment data as an array

        // SO annoying to get pagination
        $pagination = [
            "current_page" => $appointments->currentPage(),
            "last_page" => $appointments->lastPage(),
            "per_page" => $appointments->perPage(),
            "total" => $appointments->total(),
            "next_page_url" => $appointments->nextPageUrl(),
            "prev_page_url" => $appointments->previousPageUrl(),
        ];

        // Get pending patient appointment for current date
        // To test, set date to 2025-01-01
        $pendingAppointment = Appointment::where('patient_id', $patientId)
            ->where("doctor_id", $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('status', 'Pending')
            ->first();

        // Prepare the JSON response
        return response()->json([
            "appointments" => $appointmentsData,
            "pagination" => $pagination,
            "patientId" => $patientId,
            "first_name" => $patient->user->first_name ?? null,
            "last_name" => $patient->user->last_name ?? null,
            "date_of_birth" => $patient->user->date_of_birth ?? null,
            "pendingAppointment" => $pendingAppointment ?? null
        ]);
    }


    public static function showSearchPatients(Request $request)
    {
        $query = DB::table("users")
            ->join("patients", "users.id", "=", "patients.user_id")
            ->select("users.id as user_id", "users.first_name as first_name", "users.last_name as last_name", "users.date_of_birth as date_of_birth", "patients.id as patient_id", "patients.econtact_name as econtact_name", "patients.econtact_phone as econtact_phone");

        // Patient ID
        if ($request->has("patient_id") && $request->patient_id != "")
            $query->where("patients.id", $request->patient_id);

        // User ID
        if ($request->has("user_id")  && $request->user_id != "")
            $query->where("patients.user_id", $request->user_id);

        // Name
        if ($request->has("name") && $request->name != "")
            $query->whereRaw("concat(first_name, ' ', last_name) like ?", [ "%{$request->name}%" ]);

        // Age
        if ($request->has("age")  && $request->age != "")
            $query->whereRaw("TIMESTAMPDIFF(YEAR, users.date_of_birth, NOW()) = ?", [ $request->age ]);

        // Emergency contact phone
        if ($request->has("emergency_contact")  && $request->emergency_contact != "")
            $query->where("patients.econtact_phone",  "like", "%{$request->emergency_contact}%");

        // Emergency contact name
        if ($request->has("emergency_contact_name")  && $request->emergency_contact_name != "")
            $query->where("patients.econtact_name", "like", "%{$request->emergency_contact_name}%");


        // dd($query->toSql(), $query->getBindings());

        // Execute the query and get the results
        $patients = $query->get();

        $patients = $patients->map(function ($patient) {
            return [
                'patient_id' => $patient->patient_id,
                'user_id' => $patient->user_id,
                'name' => "{$patient->first_name} {$patient->last_name}",
                'date_of_birth' => $patient->date_of_birth,
                'emergency_contact' => $patient->econtact_phone,
                'emergency_contact_name' => $patient->econtact_name,
            ];
        });

        // Return filtered patients in JSON format
        return response()->json([
            "data" => $patients ?? []
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public static function updatePayment(Request $request, $patientId)
    {
        /**
         * Validation
         */
        $patient = Patient::find($patientId);

        // Patient doesn't exist
        if (!$patient)
        {
            return response()->json([
                "patientId" => $patientId,
                "error" => "Patient with id {$patientId} does not exist"
            ], 404);
        }

        // Get the bill for the patient
        $bill = $patient->bill ?? null;

        // No bill (should not happen)
        if (!$bill) {
            return response()->json([
                "patientId" => $patientId,
                "error" => "Patient does not have a bill"
            ], 404);
        }

        // Validate submitted data
        $validatedData = Validator::make($request->all(), [
            "patient_id" => [ "required", "exists:patients,id" ],
            "amount" => [ "required", "numeric", "min:0", "max:$bill" ]
        ], ValidationHelper::$payment);

        // Failure
        if ($validatedData->fails()) {
            return response()->json([
                "patientId" => $patientId,
                "bill" => $bill,
                "errors" => $validatedData->errors()
            ], 400);
        }

        /**
         * Success on validation
         */
        $amount = $request->get("amount");

        $patient->update([ "bill" => ($bill - $amount) ]);

        // Return a success response
        return response()->json([
            "patientId" => $patientId,
            "message" => "$$amount has been paid",
            "bill" => $bill,
        ], 200);
    }

    public static function updateDoctorPatient(Request $request, $patientId, $doctorId)
    {
        $validatedData = Validator::make($request->all(), [
            "appointment_id" => [ "required", "exists:appointments,id" ],
            "comment" => [ "string", "nullable" ],
            "morning_meds" => [ "string", "nullable" ],
            "afternoon_meds" => [ "string", "nullable" ],
            "night_meds" => [ "string", "nullable" ]
        ]);

        // Fail
        if ($validatedData->fails())
        {
            return response()->json([
                "success" => false,
                "message" => "Could not update the comment and prescriptions",
                "errors" => $validatedData->errors() ?? [ "Invalid input(s)" ]
            ], 400);
        }

        $appointment = Appointment::find($request->appointment_id);

        // Validate that the doctor is the one for the appointment
        if ($appointment->doctor_id !== $doctorId)
        {
            return response()->json([
                "success" => false,
                "message" => "Doctor id for appointment does not match the logged in doctor",
                "errors" => [ "Could not update the comment and prescriptions" ]
            ], 400);
        }

        // Validate status to be pending
        if ($appointment->status !== "Pending")
        {
            return response()->json([
                "success" => false,
                "message" => "Cannot update an appointment that is completed or missing",
                "errors" => [ "Could not update the comment and prescriptions" ]
            ], 400);
        }

        // TODO validate that it is current date -- but, difficult to test this functionlity if this is here

        $appointment->update([
            "comment" => $request->input("comment") ?? null,
            "morning" => $request->input("morning_meds") ?? null,
            "afternoon" => $request->input("afternoon_meds") ?? null,
            "night" => $request->input("night_meds") ?? null,

            "status" => "Completed"
        ]);

        // Update bill
        UpdaterHelper::addAppointmentCharge($patientId);

        return response()->json([
            "success" => true,
            "message" => "Appointment updated successfully",
            "appointment" => $appointment
        ]);
    }

    public static function updateMissingAppointment(Request $request, $doctorId, $appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment)
        {
            return response()->json([
                "success" => false,
                "message" => "Appointment could not be found",
                "errors" => [ "Could not update the appointment status" ]
            ], 400);
        }

         // Validate that the doctor is the one for the appointment
         if ($appointment->doctor_id !== $doctorId)
         {
             return response()->json([
                 "success" => false,
                 "message" => "Doctor id for appointment does not match the logged in doctor",
                 "errors" => [ "Could not update the comment and prescriptions" ]
             ], 400);
         }

         $appointment->update([ "status" => "Missing" ]);

         return response()->json([
            "success" => true,
            "message" => "Updated successfully",
            "appointment" => $appointment
         ]);
    }

    public static function updateCaregiverHome(Request $request, $caregiverId, $patientId, $date)
    {
        /**
         * Validation
         */
        // Check if it is the correct caregiver of a patient on date
        $isCaregiverOfPatient = ControllerHelper::getPatientCaregiverByDate($patientId, $date)["caregiver_id"] === $caregiverId;

        if (!$isCaregiverOfPatient)
            abort(400, "Incorrect caregiver updating patient status or caregiver could not be found");

        // Validate sent data for updating
        $validatedData = Validator::make($request->all(), [
            "prescription_status_id" => [ "nullable", "exists:prescription_statuses,id" ],
            "meal_id" => [ "nullable", "exists:meals,id" ],

            "morning_med" => [ "nullable", "in:Missing,Pending,Completed" ],
            "afternoon_med" => [ "nullable", "in:Missing,Pending,Completed" ],
            "night_med" => [ "nullable", "in:Missing,Pending,Completed" ],

            "breakfast" => [ "nullable", "in:Missing,Pending,Completed" ],
            "lunch" => [ "nullable", "in:Missing,Pending,Completed" ],
            "dinner" => [ "nullable", "in:Missing,Pending,Completed" ]
        ]);

        // Fail
        if ($validatedData->fails())
        {
            return response()->json([
                "success" => false,
                "message" => "Could not update status",
                "errors" => $validatedData->errors()
            ], 400);
        }

        /**
         * Prescription status updation
         */
        // Validate prescription_status_id to belong to the patient
        $prescriptionStatus = PrescriptionStatus::whereHas('appointment', function($q) use ($patientId)
        {
            $q->where('patient_id', $patientId);
        })
        ->where('id', $request->get('prescription_status_id'))
        ->first();

        if ($prescriptionStatus)
        {
            // Get the columns needed to update
            $medToUpdate = [];

            if ($request->has("morning_med")) $medToUpdate["morning"] = $request->input("morning_med");
            if ($request->has("afternoon_med")) $medToUpdate["afternoon"] = $request->input("afternoon_med");
            if ($request->has("night_med")) $medToUpdate["night"] = $request->input("night_med");

            // Update if there is something to update
            // TODO actually check if the value changed
            if (!empty($prescriptionStatus)) $prescriptionStatus->update($medToUpdate);
        }

        /**
         * Meal status updation
         */
        // Validate meal_id to belong to patient
        $mealStatus = Meal::where('patient_id', $patientId)
            ->where('id', $request->get("meal_id"))
            ->whereDate("meal_date", $date)
            ->first();

        if ($mealStatus) {
            // Get the columns needed to update for meals
            $mealToUpdate = [];

            if ($request->has("breakfast")) $mealToUpdate["breakfast"] = $request->input("breakfast");
            if ($request->has("lunch")) $mealToUpdate["lunch"] = $request->input("lunch");
            if ($request->has("dinner")) $mealToUpdate["dinner"] = $request->input("dinner");

            // Update if there is something to update
            // TODO actually check if the value changed
            if (!empty($mealToUpdate)) $mealStatus->update($mealToUpdate);
        }

        // Updating did happen
        if (!empty($prescriptionStatus) || !empty($mealToUpdate))
        {
            return response()->json([
                "message" => "Patient data updated successfully",
                "prescription_status" => $prescriptionStatus,
                "meal_status" => $mealStatus
            ]);
        }

        // Nothing to update
        return response()->json([
            "message" => "Nothing to update",
            "prescription_status" => $prescriptionStatus,
            "meal_status" => $mealStatus
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
