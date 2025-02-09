<?php

namespace App\Helpers;

use DateTime;

class ValidationHelper
{
	public static $signup = [
		"role.required" => "The role field is mandatory.",
		"role.exists" => "The selected role is invalid.",

		"first_name.required" => "Please provide your first name.",
		"first_name.max" => "Your first name cannot exceed 50 characters.",

		"last_name.required" => "Please provide your last name.",
		"last_name.max" => "Your last name cannot exceed 50 characters.",

		"email.required" => "An email address is required.",
		"email.email" => "Please provide a valid email address.",
		"email.unique" => "The email address is already taken.",
		"email.max" => "Your email address cannot exceed 100 characters.",

		"date_of_birth.required" => "Date of birth is required.",
		"date_of_birth.date" => "Please provide a valid date for date of birth.",
		"date_of_birth.before" => "Date of birth must be before today.",
		"date_of_birth.date_format" => "Date of birth must be in the format YYYY-MM-DD.",

		"phone_number.required" => "Phone number is required.",
		"phone_number.max" => "Your phone number cannot exceed 20 characters.",

		"password.required" => "Password is required.",
		"password.confirmed" => "Passwords do not match.",

		"family_code.required_if" => "Family code is required if the role is Patient.",
		"family_code.in" => "The family code provided is invalid.",
		"family_code.unique" => "The family code is already in use.",
		"family_code.size" => "Family code must be exactly 16 characters.",

		"econtact_name.required_if" => "Emergency contact name is required if the role is Patient.",
		"econtact_name.max" => "Emergency contact name cannot exceed 128 characters.",

		"econtact_phone.required_if" => "Emergency contact phone is required if the role is Patient.",
		"econtact_phone.max" => "Emergency contact phone cannot exceed 20 characters.",

		"econtact_relation.required_if" => "Emergency contact relation is required if the role is Patient.",
		"econtact_relation.max" => "Emergency contact relation cannot exceed 50 characters.",
	];

	public static $roster = [
		"date.required" => "Please provide a date.",
		"date.date" => "The date format is invalid. Please use a valid date.",
		"date.after_or_equal" => "The date must be today or a future date.",
		"date.unique" => "A roster already exists for this date.",

		"supervisor.required" => "A supervisor must be selected.",
		"supervisor.exists" => "The selected supervisor does not exist. Please choose a valid supervisor.",

		"doctor.required" => "A doctor must be selected.",
		"doctor.exists" => "The selected doctor does not exist. Please choose a valid doctor.",

		"caregivers.required" => "At least one caregiver must be selected.",
		"caregivers.array" => "The caregivers must be selected as an array.",
		"caregivers.size" => "You must select 4 caregivers.",

		"caregivers.*.exists" => "Each caregiver must exist.",

		"caregivers.0.distinct" => "Caregiver 1 has a duplicate value.",
		"caregivers.1.distinct" => "Caregiver 2 has a duplicate value.",
		"caregivers.2.distinct" => "Caregiver 3 has a duplicate value.",
		"caregivers.3.distinct" => "Caregiver 4 has a duplicate value.",
	];

	public static $payment = [
		'patient_id.required' => 'Please enter the patient ID.',
        'patient_id.exists' => 'The patient does not exist.',

        'amount.required' => 'Please enter an amount to proceed with the payment.',
        'amount.numeric' => 'The payment amount must be a valid number.',
        'amount.min' => 'The payment amount cannot be less than 0.',
        'amount.max' => 'The payment amount cannot exceed the patient\'s bill of :max.',
    ];

	public static $familyHome = [
		"patient_id.required" => "The patient ID is mandatory.",
		"patient_id.size" => "The patient ID must be exactly 16 characters long.",
		"patient_id.exists" => "The patient ID does not exist in our records.",

		"family_code.required" => "The family code is required.",
		"family_code.size" => "The family code must be exactly 16 characters long.",
		"family_code.exists" => "The family code does not exist in our records.",
	];

	/**
	 * Validate the date format. Aborts if not valid
	 *
	 * @param string $date
	 * @param string $return date (Y-m-d format) or datetime
	 * @return string|DateTime
	 */
	public static function validateDateFormat($date, $return = "date")
	{
		// Reformat date string
		if (!is_string($date))
			$date = $date->format('Y-m-d');

		// Check if it is date
		if (!strtotime($date))
			abort(400, "Not a date");

		$datetime = DateTime::createFromFormat("Y-m-d", $date);

		if (!$datetime || !$datetime->format("Y-m-d") === $date)
			abort(400, "Invalid date format");

		return $return === "date" ? $date : $datetime;
	}
}
