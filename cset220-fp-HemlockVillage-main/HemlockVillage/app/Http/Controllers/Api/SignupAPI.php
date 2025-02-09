<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Helpers\ModelHelper;
use App\Helpers\ValidationHelper;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use App\Models\Patient;

class SignupAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store(Request $request)
    {
         // Validate user info
         $validatedUser = Validator::make($request->all(), [
            "role" => [ "required", "exists:roles,id" ], // Check that role id actually exists
            "first_name" => [ "required", "max:50" ],
            "last_name" => [ "required", "max:50" ],
            "email" => [ "bail", "required", "email", "unique:users", "max:100" ],
            "date_of_birth" => [ "required", "date", "before:". date("Y-m-d"), "date_format:Y-m-d" ], // Check that date is before current date
            "phone_number" => [ "required", "max:20" ],
            "password" => [ "bail", "required", "confirmed" ], // Check that password matches confirmed password input field
            "family_code" => [ "required_if:role," . Role::getId("Patient"), "in:" . session("familyCode"), "unique:patients", "size:16"], // Check that family code was not altered
            "econtact_name" => [ "required_if:role," . Role::getId("Patient"), "max:128" ],
            "econtact_phone" => [ "required_if:role," . Role::getId("Patient"), "max:20" ],
            "econtact_relation" => [ "required_if:role," . Role::getId("Patient"), "max:50" ],
        ], ValidationHelper::$signup);

        // Fails validation for user
        if ($validatedUser->fails())
        {
            return response()->json([
                "success" => false,
                "message" => "Invalid input(s). Please try again.",
                "errors" => $validatedUser->errors()
            ], 400);
        }

        // Hash the password
        $request->merge([
            "password" => Hash::make($request->get("password"))
        ]);

        // Create record in users table
       $user = User::create([
            "first_name" => $request->get("first_name"),
            "last_name" => $request->get("last_name"),
            "email" => $request->get("email"),
            "date_of_birth" => $request->get("date_of_birth"),
            "phone_number" => $request->get("phone_number"),
            "password" => $request->get("password"),
            "role_id" => $request->get("role"),
            "approved" => 0
        ]);

        $userId = $user->id;

        // Add data into patient table if patient role
        if ($request->role == Role::getId("Patient"))
        {
            Patient::create([
                "id" => ModelHelper::getRandomString(),
                "user_id" => $userId,
                "family_code" => $request->get("family_code"),
                "econtact_name" => $request->get("econtact_name"),
                "econtact_phone" => $request->get("econtact_phone"),
                "econtact_relation" => $request->get("econtact_relation")
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "User account successfully created.",
            "user" => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
