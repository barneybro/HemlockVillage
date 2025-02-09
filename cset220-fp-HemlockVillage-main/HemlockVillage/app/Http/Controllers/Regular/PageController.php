<?php

namespace App\Http\Controllers\Regular;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserAPI;
use App\Http\Controllers\Api\HomeAPI;

use App\Models\Patient;
use App\Models\Employee;
use App\Models\Roster;

use App\Helpers\ControllerHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Api\APIController;
use App\Models\Appointment;
use Carbon\Carbon;

class PageController extends Controller
{
    public static function landing()
    {
        return view("landing");
    }

    public static function users()
    {
        $data = UserAPI::index();

        $pagination = [
            "current_page" => $data->currentPage(),
            "last_page" => $data->lastPage(),
            "per_page" => $data->perPage(),
            "total" => $data->total(),
            "next_page_url" => $data->nextPageUrl(),
            "prev_page_url" => $data->previousPageUrl(),
            "pages" => range(max(1, $data->currentPage() - 2), min($data->lastPage(), $data->currentPage() + 2))
        ];

        return view("users")->with([
            "data" => UserAPI::index(),
            "pagination" => $pagination
        ]);
    }

    public static function home()
    {
        $userId = Auth::user()->id;
        $accessLevel = ControllerHelper::getUserAccessLevel($userId);


        // Return view depending on access level of user
        switch ($accessLevel)
        {
            case 1: // Admin
                return redirect("/users");

            case 2: // Supervisor
                return redirect("/users");

            case 3: // Doctor
                // return  HomeAPI::indexDoctor($userId);

                return view("doctorshome")->with([
                    "data" => HomeAPI::indexDoctor($userId)
                ]);

            case 4: // Caregiver
                $caregiverId = Employee::getId($userId);

                // return HomeAPI::showCaregiver($caregiverId, "2024-11-03");
                // return HomeAPI::showCaregiver($caregiverId, Carbon::today());
                // $date = "2024-11-03";
                $date = Carbon::today()->toDateString();
                // $date = "2025-01-10";

                $response = HomeAPI::showCaregiver($caregiverId, $date);
                $jsonDecoded = json_decode($response->getContent(), true);

                if ($response->getStatusCode() === 201)
                {
                    return view("caregivershome")
                        ->with("message", $jsonDecoded["message"] ?? "Could not locate data")
                        ->with("date", $date);
                }

                return view("caregivershome")
                    ->with("data", $jsonDecoded["data"] ?? [])
                    ->with("date", $date)
                    ->with("groupNum", $jsonDecoded["groupNum"] ?? '');

            case 5: // Patient
                $patientId = Patient::getId($userId);

                // return HomeAPI::showPatient($patientId, Carbon::today());
                // return HomeAPI::showPatient($patientId, "2024-11-03");

                return view("patientshome")->with([
                    "data" => HomeAPI::showPatient($patientId, Carbon::today())
                ]);

            case 6: // Family
                /**
                 * Default home page with inputs only
                 */
                if (empty(request()->query()))
                    // return Carbon::today()->format("Y-m-d");
                    return view("familyhome")->with("date", Carbon::today()->format("Y-m-d"));

                /**
                 * Validate that both patient id and family code are submitted
                 */
                $validatedPatient = Validator::make(request()->all(), [
                    "patient_id" => [ "required", "size:16", "exists:patients,id" ],
                    "family_code" => [ "required", "size:16", "exists:patients,family_code" ],
                ], ValidationHelper::$familyHome);

                // $date = "2024-11-03";
                $date = Carbon::today()->format("Y-m-d");

                $patientId = request()->get("patient_id");
                $familyCode = request()->get("family_code");

                session()->flash("familyCode", $familyCode);
                session()->flash("patientId", $patientId);

                // Fails validation
                if ($validatedPatient->fails())
                    return redirect()->back()->withErrors($validatedPatient->errors())
                        ->with("date", $date);

                /**
                 * Retrieve response to check if success or failure
                 */
                $response = HomeAPI::showFamily($patientId, $familyCode, $date);
                $jsonContent = json_decode($response->getContent(), true);

                // Fails Validation
                if ($response->getStatusCode() !== 200)
                {
                    $errors = $jsonContent["errors"] ?? ["Invalid input(s). Please try again."];

                    return redirect()->back()->withErrors($errors)
                        ->with("date", $date);;
                }

                // Success
                // return $jsonContent["data"];

                return view("familyhome")->with("data", $jsonContent["data"])
                    ->with("date", $date);;

            case null:
                return response()->json(['error' => 'Could not find user id or access level'], 404);

            default:
                return "No home page for your access level";
        }
    }

    public static function homeWithDate($date)
    {
        $userId = Auth::user()->id;
        $accessLevel = ControllerHelper::getUserAccessLevel($userId);

        switch ($accessLevel)
        {
            case 3: // Doctor

                $doctorId = Employee::getId($userId);

                if (!$doctorId)
                    abort(500, "Could not find an employee id associated with your user id");

                // --- Uncomment to see the data only
                // return [
                //     "old" => HomeAPI::indexDoctor($userId),
                //     "upcoming" =>HomeAPI::showDoctor($doctorId, $date)
                // ];

                return view("doctorshome")->with([
                    "data" => HomeAPI::indexDoctor($userId),
                    "upcoming" =>HomeAPI::showDoctor($doctorId, $date)
                ]);

            case 5:
                $patientId = Patient::getId($userId) ?? null;

                if (!$patientId) abort(400,"Patient could not be found");

                // To test, pass date as 2024-11-03
                // return HomeAPI::showPatient($patientId, $date);

                return view("patientshome")->with("data", HomeAPI::showPatient($patientId, $date));

            default:
                return "You should not have access to this page otherwise";
        }
    }

    public static function report()
    {
        // return APIController::getReport(Carbon::today());
        // return APIController::getReport("2024-11-01");
        // return APIController::getReportNew("2024-11-01");
        // return APIController::getReportNew("2024-12-11");

        // TODO pagiante in APIController::getReport

        // return view("adminreport")->with("data", APIController::getReport("2024-11-03"));
        // return view("adminreport")->with("data", APIController::getReportNew("2024-12-11", [ "Missing", "Pending" ]));

        if (request()->has("date"))
            return view("adminreport")->with("data", APIController::getReportNew(request()->date, [ "Pending", "Missing" ]))->with("date", request()->date);

        return view("adminreport")->with("data", APIController::getReportNew(Carbon::today(), [ "Pending", "Missing" ]));
    }

    /*
    *
    *   Roster
    *
    */
    public static function indexrosterForm()
    {
        return view("newroster")->with([
            "currentDate" => Carbon::today()->format("Y-m-d"),
            "employees" => APIController::indexRosterCreation()
        ]);
    }

    public static function storeRosterForm(Request $request)
    {
        /**
         * Check status
         */
        $response = APIController::storeRosterForm($request);
        $jsonDecoded = json_decode($response->getContent(), true);

        // Fails validation
        if ($response->getStatusCode() !== 200)
        {
            $errors = $jsonDecoded["errors"] ?? [ "Invalid inputs(s). Please try again" ];

            return redirect()->back()->withErrors($errors)->withInput(); // Pass back the inputted values as well
        }

        // Success
        return redirect()->back()
            ->with("message", $jsonDecoded["message"] ?? "Roster for date {$request->get('date')} has been created");
    }

    public static function showRoster()
    {
        /**
         * Check response status
         */
        // To test, pass date as 2024-11-03
        $reponse = APIController::showRoster(Carbon::today()->format("Y-m-d"));
        // $reponse = APIController::showRoster("2024-11-03");
        $jsonDecoded = json_decode($reponse->getContent(), true);

        // No roster
        if ($reponse->getStatusCode() !== 200)
        {
            return view("roster")->with([
                "message" => $jsonDecoded["message"],
                "data" => $jsonDecoded["data"]
            ]);
        }

        // Success
        return view("roster")->with([
            "data" => $jsonDecoded["data"]
        ]);
    }

    /**
     *
     * Payment
     *
     */

    public static function indexPayment()
    {
        return view("payments");
    }

    public static function showPayment($patientId)
    {
        $response = APIController::showPayment($patientId);
        $jsonDecoded = json_decode($response->getContent(), true);

        if ($response->getStatusCode() !== 200)
        {
            return view("payments")->with([
                "patientId" => $jsonDecoded["patientId"] ?? $patientId,
                "error" => $jsonDecoded["error"] ?? "Patient with id { {$patientId} } does not exist"
            ]);
        }

        return view("payments")->with([
            "patientId" => $jsonDecoded["patientId"] ?? $patientId,
            "bill" => $jsonDecoded["bill"] ?? 0,
        ]);
    }

    public static function updatePayment(Request $request, $patientId)
    {
        /**
         * Check status
         */
        $response = APIController::updatePayment($request, $patientId);
        $jsonDecoded = json_decode($response->getContent(), true);

        // Failure
        if ($response->getStatusCode() !== 200)
        {
            $errors =  $jsonDecoded["errors"] ?? [ "Patient with id { {$patientId} } does not exist OR the invalid amount" ];

            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        // Success
        return redirect()->back()
            ->with("message", $jsonDecoded["message"] ?? "$$request->amount has been paid");
    }

    /**
     *
     * Doctor's Patient
     *
     */
    public static function showDoctorPatient($patientId)
    {
        // $date = "2024-12-25";
        $date = Carbon::today();

        $doctorId = DB::table("employees")
            ->where("user_id", Auth::user()->id)
            ->first()
            ->id ?? null;

        // To test, set date to "2025-01-01"
        $appointments = APIController::showDoctorPatient($doctorId, $patientId, $date);
        $jsonDecoded = json_decode($appointments->getContent(), true);

        return view("patientofdoc")->with([
            "pendingAppointment" => $jsonDecoded["pendingAppointment"] ?? null,
            "appointments" => $jsonDecoded["appointments"] ?? [],
            "pagination" => $jsonDecoded["pagination"] ?? [],
            "patientId" => $jsonDecoded["patientId"] ?? null,
            "first_name" => $jsonDecoded["first_name"] ?? null,
            "last_name" => $jsonDecoded["last_name"] ?? null,
            "date_of_birth" => $jsonDecoded["date_of_birth"] ?? null,
            "date" => $date
        ]);
    }

    public static function updateDoctorPatient(Request $request, $patientId)
    {
        // Used to validate correct doctor for appointment
        $doctorId = DB::table("employees")
            ->where("user_id", Auth::user()->id)
            ->first()
            ->id ?? null;

        $response =  APIController::updateDoctorPatient($request, $patientId, $doctorId);
        $jsonDecoded = json_decode($response->getContent(), true);

        if ($response->getStatusCode() !== 200)
        {
            return redirect()->back()
                ->withErrors($jsonDecoded["errors"] ?? [ "Invalid input(s)" ]);
        }

        return redirect()->back()
            ->with("message", $jsonDecoded["message"] ?? "Appointment updated successfully");
    }

    public static function updateMissingAppointment(Request $request, $appointmentId)
    {
         // Used to validate correct doctor for appointment
         $doctorId = DB::table("employees")
            ->where("user_id", Auth::user()->id)
            ->first()
            ->id ?? null;

        $response = APIController::updateMissingAppointment($request, $doctorId, $appointmentId);
        $jsonDecoded = json_decode($response->getContent(), true);

        if ($response->getStatusCode() !== 200)
        {
            return redirect()->back()
                ->withErrors($jsonDecoded["errors"] ?? [ "Error with updating" ]);
        }

        return redirect()->back()
            ->with("message", $jsonDecoded["message"] ?? "Appointment updated successfully");
    }

    /**
     *
     * Cargiver
     *
     */
    public static function updateCaregiverHome(Request $request, $patientId)
    {
        $caregiverId = Auth::user()->employees->first()->id ?? null;

        // $response = APIController::updateCaregiverHome($request, $caregiverId, $patientId, "2024-11-03");
        $response = APIController::updateCaregiverHome($request, $caregiverId, $patientId, Carbon::today()->toDateString());

        $jsonDecoded = json_decode($response->getContent(), true);

        if ($response->getStatusCode() !== 200)
        {
            return redirect()->back()
            ->withErrors($jsonDecoded["errors"] ?? [ "Fail to update" ]);
        }

        return redirect()->back()
            ->with("message", $jsonDecoded["message"] ?? "No issues with updating");
    }

    /**
     *
     * Search
     *
     */
    public static function searchPatients(Request $request)
    {
        // Default search page
        if (count(request()->all()) < 1)
            return view("newPatientSearch");

        // Actaully searching
        $response = APIController::showSearchPatients($request);
        $jsonDecoded = json_decode($response->getContent(), true);

        return view("newPatientSearch")
            ->with("data", $jsonDecoded["data"] ?? []);
    }

    /**
     *
     * Appointment Scheduling
     *
     */
    public static function indexSchedule()
    {
        // Default
        if (count(request()->all()) == 0)
            return view("doctorsappointment");

        /**
         * GET form submisssion validation
         */
        $validatedData = Validator::make(request()->all(), [
            "appointment_date" => [ "required", "date", "after_or_equal:" . date("Y-m-d") ],
            "patient_id" => [ "required", "size:16", "exists:patients,id" ]
        ]);

        // Failure
        if ($validatedData->fails())
        {
            return redirect()->back()->withErrors($validatedData->errors())
                ->withInput();
        }

        $appointmentDate = request()->get("appointment_date");

        // Don't need to validate since Validator already does that
        $patient = Patient::find(request()->get("patient_id"));

        $roster = Roster::with('doctor')->whereDate("date_assigned", $appointmentDate)->first();

        // return $roster;

        // No roster found
        if (!$roster)
        {
            // Persist the data
            // why is it working now but not earlier
            // session()->flash("appointment_date", $appointmentDate);
            // session()->flash("patient_id", $patient->id);

            return redirect()->back()
                ->withErrors([ "roster" => "No roster created for " . Carbon::parse($appointmentDate)->format("M d, Y") ])
                ->with("patient_id", request()->patient_id);
        }

        if (!$roster->doctor)
        {
            return redirect()->back()
                ->withErrors([ "doctor" => "No doctor scheduled" ])
                ->withInput();
        }

        return view("doctorsappointment")->with([
            "appointmentDate" => $appointmentDate ?? null,
            "patientId" => $patient->id ?? null,
            "patientName" => "{$patient->user->first_name} {$patient->user->last_name}" ?? null,
            "doctorName" => $roster->doctor ? "{$roster->doctor->user->first_name} {$roster->doctor->user->last_name}" : null,
            "doctorId" => $roster->doctor->id ?? null
        ]);
    }

    public static function storeSchedule(Request $request)
    {
        /**
         * Validation
         */
        $validatedData = Validator::make($request->all(), [
            "appointment_date" => [ "required", "date", "after_or_equal:" . date("Y-m-d") ],
            "patient_id" => [ "required", "string", "size:16", "exists:patients,id" ],
            "doctor" => [ "required", "numeric", "exists:employees,id" ]
        ]);

        // Fail
        if ($validatedData->fails())
        {
            return redirect()->back()->withErrors($validatedData->errors())->withInput();
        }

        // Format the appointment date if needed
        $appointmentDate = Carbon::parse(request()->get("appointment_date"))->format("Y-m-d");

        // Don't need to validate since Validator already does that
        $patient = Patient::find(request()->get("patient_id"));

        // Validate that roster exists for date
        $roster = Roster::whereDate("date_assigned", $appointmentDate)->first();

        // No roster
        if (!$roster)
            return redirect()->back()->withErrors([ "roster" => "No roster created for " . Carbon::parse($appointmentDate)->format("M d, Y") ])->withInput();

        $doctorId = request()->get("doctor");

        // Validate that doctor is on roster
        if ($roster->doctor_id != $doctorId)
            return redirect()->back()->withErrors([ "doctor" => "This doctor is not on the roster for" . Carbon::parse($appointmentDate)->format("M d, Y") ])->withInput();

        // Validate that no duplicate value of date and patient for appointment (that is how the database is setup currrently)
        $duplicateAppointment = Appointment::whereDate("appointment_date", $appointmentDate)
            ->where("patient_id", $patient->id)->first();

        // Duplicate
        if ($duplicateAppointment)
            return redirect()->back()->withErrors([ "duplicate" => "Sorry, at this moment, patients cannot be scheduled for multiple appointments on one day" ])->withInput();

        $appointment = Appointment::create([
            "patient_id" => $patient->id,
            "date_scheduled" => Carbon::today()->toDateString(),
            "appointment_date" => $appointmentDate,
            "doctor_id" => $doctorId
        ]);

        return redirect()->back()->with("message", "{$patient->user->first_name} {$patient->user->last_name} { $patient->id } has been scheduled for an appointment for $appointmentDate");
    }
}
