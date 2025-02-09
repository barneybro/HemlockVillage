<?php

namespace App\Helpers;

use Carbon\Carbon;

use App\Models\Patient;
use App\Models\Meal;

use Illuminate\Support\Facades\Log;

class UpdaterHelper
{
	private static $dailyCharge = 10;
	private static $monthlyPrescriptionCharge = 5;
	private static $appointmentCharge = 50;

	// TODO add getter and setter functions for charge amounts

	/**
	 * Get the difference in full days between two dates.
	 *
	 * @param string|DateTime|Carbon $date1
	 * @param string|DateTime|Carbon $date2
	 * @return int The number of days between $date1 and $date2
	 */
	public static function getDaysDifference($date1, $date2)
	{
		/**
		 * Validation
		 */
		ValidationHelper::validateDateFormat($date1);
		ValidationHelper::validateDateFormat($date2);

		// Converts to Carbon instance if not already
		if (!($date1 instanceof Carbon))
			$date1 = Carbon::parse($date1);

		if (!($date2 instanceof Carbon))
			$date2 = Carbon::parse($date2);

		return $date1->diffInDays($date2);
	}

	/**
	 * Get the difference in full months between two dates.
	 *
	 * @param string|DateTime|Carbon $date1
	 * @param string|DateTime|Carbon $date2
	 * @return int The number of full months between $date1 and $date2
	 */
	public static function getFullMonthsDifference($date1, $date2)
	{
		/**
		 * Validation
		 */
		ValidationHelper::validateDateFormat($date1);
		ValidationHelper::validateDateFormat($date2);

		// Convert to Carbon instance if not already
		if (!($date1 instanceof Carbon))
			$date1 = Carbon::parse($date1);

		if (!($date2 instanceof Carbon))
			$date2 = Carbon::parse($date2);

		// Check the chronological order of the dates passed
		if ($date1 > $date2)
			list($date1, $date2) = [ $date2, $date1 ]; // Switch the dates to make them in chronological order

		return (int) $date1->diffInMonths($date2);
	}

	/**
	 * Add a daily charge to a patient's bill if not added for the current day
	 */
	public static function addDailyCharge($patientId)
	{
		/**
		 * Validation
		 */
		$patient = Patient::find($patientId);

		if (!$patient)
		{
			abort(400, "Patient could not be found");
		}

		/**
		 * Days calculation
		 */
		$currentDate = Carbon::today();
		$lastUpdated = Carbon::parse($patient->daily_updated_date);

		// Do nothing if no last updated date
		if (!$lastUpdated) return;

		$daysDifference = self::getDaysDifference($lastUpdated, $currentDate);

		/**
		 * Charge calculation
		 */
		// Don't do anything if last updated today
		if ($daysDifference === 0)
			return;

		$patient->update([
			"bill" => ( $patient->bill + $daysDifference * self::$dailyCharge ),
			"daily_updated_date" => $currentDate->format("Y-m-d")
		]);

		return response()->json([
			"message" => "The bill has been updated",
			"patient" => $patient
		]);
	}

	/**
	 * Add a monthly charge to a patient's bill if not added at the current day
	 */
	public static function addMonthlyPrescriptionCharge($patientId)
	{
		/**
		 * Validation
		 */
		$patient = Patient::find($patientId);

		if (!$patient)
		{
			abort(400, "Patient could not be found");
		}

		/**
		 * Days calculation
		 */
		$currentDate = Carbon::today();
		$lastUpdated = Carbon::parse($patient->prescription_updated_date);

		// Do nothing if no last updated date
		if (!$lastUpdated) return;

		$monthsDifference = self::getFullMonthsDifference($lastUpdated, $currentDate);

		/**
		 * Charge calculation
		 */
		// Don't do anything if last updated within the last month
		if ($monthsDifference < 1)
			return;

		$patient->update([
			"bill" => ( $patient->bill + self::$monthlyPrescriptionCharge * $monthsDifference ),
			"prescription_updated_date" => $lastUpdated->addMonths($monthsDifference)
		]);

		return response()->json([
			"message" => "The bill has been updated",
			"patient" => $patient
		]);
	}

	/**
	 * Add an appointment charge. To be used when appointment is completed
	 */
	public static function addAppointmentCharge($patientId)
	{
		/**
		 * Validation
		 */
		$patient = Patient::find($patientId);

		if (!$patient)
		{
			abort(400, "Patient could not be found");
		}

		/**
		 * Adding charge
		 */
		$patient->update([
			"bill" => ( $patient->bill + self::$appointmentCharge ),
		]);

		return response()->json([
			"message" => "The bill has been updated",
			"patient" => $patient
		]);
	}


	/**
	 * Set past meal status to "Missing" if they were still "Pending" if the meal exists. Otherwise, create one with status all "Missing"
	 * @param boolean $isCreating
	 */
	public static function updatePastMeal($mealId = null, $patientId = null, $date = null)
	{
		// Attempt to find meal
		// Need to check if mealId is null because Meal::where("id", $mealId) == null is false for some reason when mealId is null
		$meal = $mealId ? Meal::where("id", $mealId)->first() : null;

		// TODO validate inputs

		// Meal does not exist and no patientId or date
		if (!$meal && (!$patientId || !$date)) return;

		// Create new meal in the past if it does not exist
		if (!$meal)
		{
			try
			{
				$newMeal = Meal::create([
					"patient_id" => $patientId,
					"meal_date" => $date,
					"breakfast" => "Missing",
					"lunch" => "Missing",
					"dinner" => "Missing"
				]);

				return response()->json([
					"message" => "Meal has been created",
					"meal" => $newMeal
				]);
			}
			catch (\Throwable  $e)
			{
				Log::error("Failed to create meal: " . $e->getMessage(), [
					"patient_id" => $patientId,
					"meal_date" => $date,
					"error" => $e->getTraceAsString(),  // Add the full error trace for debugging
				]);

				return response()->json([
                    "error" => "Failed to create meal. Please try again later."
                ], 500);
			}
		}

		// Otherwise if there is a meal, update
		$mealTimes = [ "breakfast", "lunch", "dinner" ];

		foreach ($mealTimes as $time)
		{
			if ($meal->{$time} === "Pending")
				$meal->update([ $time => "Missing"]);
		}

		$meal->refresh();

		return response()->json([
			"message" => "Meal has been created",
			"meal" => $meal
		]);
	}

	/**
	 * Create a new meal status for a patient for a date if it does not exist
	 * @param string|date|Carbon $date The date to insert
	 * @param string|date|Carbon|null $currentDate The date to act as the current date of inserting. If null, it will be Carbon::today()
	 */
	public static function updateMeal($patientId, $date, $currentDate = null)
	{
		/**
		 * Validate date format
		 */
		// Carbon parse so it can be used for date comparison later
		$date = Carbon::parse(ValidationHelper::validateDateFormat($date));
		$currentDate = $currentDate ? Carbon::parse(ValidationHelper::validateDateFormat($currentDate)) : Carbon::today();

		/**
		 * Patient id validation
		 */
		$patient = Patient::find($patientId);

		if (!$patient)
		{
			return response()->json([
				"patientId" => $patientId,
				"error" => "Could not find a patient with that ID"
			], 400);
		}

		/**
		 * Meal validation
		 */
		$meal = Meal::where("patient_id", $patientId)
			->whereDate("meal_date", $date)->first();

		// In the past
		if ($date->lt($currentDate))
		{
			// Update all "Pending" to "Missing" or create a new one
			return self::updatePastMeal($meal->id ?? null, $patientId, $date);
		}

		// Meal does not exist for current date
		if (!$meal)
		{
			$newMeal = Meal::create([
				"patient_id" => $patientId,
				"meal_date" => $date,
				"breakfast" => "Pending",
				"lunch" => "Pending",
				"dinner" => "Pending",
			]);


			return response()->json([
				"message" => "Meal has been created",
				"meal" => $newMeal
			]);
		}

		// Meal exists for current date
		return response()->json([
			"message" => "Meal has been created",
			"meal" => $meal
		]);
	}
}
