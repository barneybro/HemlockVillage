<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

use App\Models\PrescriptionStatus;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Employee;

use Carbon\Carbon;

class ControllerHelper
{
	/**
	 * Get the access level associated with the user
	 *
	 * @param int $userId
	 * @param int|null The access level if found
	 */
	public static function getUserAccessLevel($userId)
	{
		return DB::table("roles")
			->where("id", "=", function ($query) use ($userId)
			{
				$query->select("role_id")->from("users")->where("id", $userId);
			})->value("access_level");
	}

	/**
	 * @return Illuminate\Support\Collection Collection of appointments with patient details
	 */
	public static function getDoctorPatientsUpToDate($doctorId, $date)
	{
		/**
         * Validation
         */
        // Invalid date format
        if (!strtotime($date))
            abort(400, "Invalid date format");

		// Doctor does not exist
		if (!Appointment::where("doctor_id", $doctorId)->first())
			abort(400, "Doctor does not exist");

		/**
		 * Data retrieval
		 */

		$appointments = Appointment::with([
			"doctor" => fn($q) => $q->select("id"),
			"patient"=> fn($q) => $q->select("id", "user_id"),
			"patient.user" => fn($q) => $q->select("id", "first_name", "last_name")
		])
		->where("doctor_id", $doctorId)
		->whereDate("appointment_date", "<=", $date)
		->whereDate("appointment_date", ">=", Carbon::today()->format("Y-m-d"))
		->where("status", "Pending")
		->select("id", "appointment_date", "doctor_id", "patient_id")
		->orderBy("appointment_date", "asc")
		->orderBy("patient_id", "asc")
		->get();

		return $appointments->map( function ($a)
		{
			$user = $a->patient->user;

			return [
				"appointment_id" => $a->id,
				"patient_id" => $a->patient->id,
				"patient_name" => "{$user->first_name} {$user->last_name}",
				"appointment_date" => $a->appointment_date
			];
		});
	}

	/**
	 * Check if a patient has an apppointment for a given date
	 * @return boolean true if an appointment can be found for the given date; false otherwise
	 */
	public static function appointmentExists($patientId, $date)
	{
		return DB::table("appointments")
			->where("patient_id", $patientId)
			->whereDate("appointment_date", $date)
			->exists();
	}

	/**
	 * Get the prescription status, appointment info for prescriptions, and the doctor info for a patient on a given date
	 * @param string $patienId The id of hte patient
	 * @param string $date The date of the appointment
	 */
	public static function getPatientPrescriptionStatusAppointmentByDate($patientId, $date)
	{
		// ======== TODO if there is no prescription status for today, create a default one using the most recent appointment id that is marked as completed

		return PrescriptionStatus::with([
			"appointment" => fn($q) => $q->select("id", "patient_id", "doctor_id", "status", "comment", "morning", "afternoon", "night"),
			"appointment.doctor.user" => fn($q) => $q->select("id", "first_name", "last_name")
		])
        ->where("prescription_date", $date)
        ->whereHas("appointment", function ($query) use ($patientId)
        {
            $query->where("patient_id", "=", $patientId);
        })
		->select("id", "appointment_id", "morning", "afternoon", "night")
		->first();
	}

	/**
	 * Get the prescription info from the appointments table
	 *
	 * @param model $patientPrescriptionStatusAppointment The result of the function getPatientPrescriptionStatusAppointmentByDate($patientId, $date)
	 */
	public static function getPatientPrescriptionByDate($patientPrescriptionStatusAppointment)
	{
		$appointment = $patientPrescriptionStatusAppointment->appointment ?? null; // Appointment and doctor info

		return [
			"morning" => $appointment->morning ?? null,
			"afternoon" => $appointment->afternoon ?? null,
			"night" => $appointment->night ?? null,
		];
	}

	public static function getPatientAppointmentByDate($patientId, $date)
	{
		$appointment = Appointment::where("patient_id", $patientId)
			->whereDate("appointment_date", $date)->first();

		return [
			"appointment_id" => $appointment->id ?? null,
			"doctor_id" => $appointment->doctor_id ?? null,
			"doctor_name" => $appointment  ? "{$appointment->doctor->user->first_name} {$appointment->doctor->user->last_name}" : null,
			"status" => $appointment->status ?? null,
			"comment" => $appointment->comment ?? null,
			"morning" => $appointment->morning ?? null,
			"afternoon" => $appointment->afternoon ?? null,
			"night" => $appointment->night ?? null,
		];
	}

	public static function getSimplePatientPrescriptionStatusByDate($appointmentId, $date)
	{
		$prescriptionStatus = PrescriptionStatus::where("appointment_id", $appointmentId)
			->whereDate("prescription_date", $date)->first();

		return [
			"morning" => $prescriptionStatus->morning ?? null,
			"afternoon" => $prescriptionStatus->afternoon ?? null,
			"night" => $prescriptionStatus->night ?? null,
		];
	}

	/**
	 * Get the prescription info from the appointments table
	 *
	 * @param model $patientPrescriptionStatusAppointment The result of the function getPatientPrescriptionStatusAppointmentByDate($patientId, $date)
	 */
	public static function getPatientPrescriptionStatusByDate($patientPrescriptionStatusAppointment)
	{
		return [
			"morning" => $patientPrescriptionStatusAppointment->morning ?? null,
			"afternoon" => $patientPrescriptionStatusAppointment->afternoon ?? null,
			"night" => $patientPrescriptionStatusAppointment->night ?? null,
		];
	}

	public static function getPatientMealStatusByDate($patientId, $date)
	{
		$meal = DB::table("meals")->where([
            [ "patient_id", "=", $patientId ],
            [ "meal_date", "=", $date ]
        ])->first();

		return [
			"meal_id" => $meal->id ?? null,
			"status_data" => [
				"breakfast" => $meal->breakfast ?? null,
				"lunch" => $meal->lunch ?? null,
				"dinner" => $meal->dinner ?? null,
				]
		];
	}

	/**
	 * Translates the caregiver column name in the rosters table into the group number as a numeric string
	 * To be used to identify which group number a caregiver is responsible for
	 */
	public static function convertRosterCaregiverToNumeric($caregiverColumn)
	{
		switch ($caregiverColumn)
		{
			case "caregiver_one_id": return "1";
			case "caregiver_two_id": return "2";
			case "caregiver_three_id": return "3";
			case "caregiver_four_id": return "4";
			default: return null;
		}
	}

	public static function convertGroupNumToRosterCaregiverColumn($groupNum)
	{
		switch ($groupNum)
		{
			case "1": return "caregiver_one_id";
			case "2": return "caregiver_two_id";
			case "3": return "caregiver_three_id";
			case "4": return "caregiver_four_id";
			default: return null;
		}
	}

	/**
	 * Get the caregiver id and name for a patient on a given date
	 */
	public static function getPatientCaregiverByDate($patientId, $date)
	{
		$patient = ModelHelper::getRow(Patient::class, "id", $patientId);

		// Patient could not be found
		if (!$patient) abort(403, "Patient not found");

		// Column to get the employee id in the rosters table
		$column = self::convertGroupNumToRosterCaregiverColumn($patient->group_num);

		// Invalid group number
		if (!$column)
			return [
				"caregiver_id" => "Patient group number is invalid",
				"caregiver_name" => "Patient group number is invalid"
			];

		$caregiver = DB::table("rosters")
			->join("employees", "rosters.$column", "employees.id")
			->join("users", "employees.user_id", "users.id")
			->whereDate("date_assigned", "=", $date)
			->select("$column","users.first_name", "users.last_name")
			->first();

		return [
			"caregiver_id" => $caregiver->$column ?? null,
			"caregiver_name" => $caregiver ? "{$caregiver->first_name} {$caregiver->last_name}" : null
		];
	}

	/*
	*
	* Roster
	*
	*/
	/**
	 * Get all the employees with a certain role
	 * @param string $role The role to search for
	 */
	public static function getEmployeeForRosterCreation($role)
	{
		/**
		 * Role Validation
		 */
		$roles = [ "Supervisor", "Doctor", "Caregiver" ];

		if (!in_array($role, $roles))
			abort(400, "Invalid role");

		/**
		 * Data retrieval
		 */
		$employees = Employee::with([
			"user" => fn($q) => $q->select("id", "first_name", "last_name", "role_id"),
			"user.role" => fn($q) => $q->select("id", "role")
		])
        ->whereHas("user.role", function ($q) use ($role)
		{
            $q->where("role", $role);
        })
        ->select("id", "user_id")
        ->get();

		return $employees->map( function ($e)
		{
			return [
				"employee_id" => $e->id ?? null,
				"name" => $e->user ? "{$e->user->first_name} {$e->user->last_name}" : null
			];
		});
	}
}
