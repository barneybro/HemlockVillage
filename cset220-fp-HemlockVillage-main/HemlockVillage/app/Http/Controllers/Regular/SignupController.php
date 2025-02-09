<?php

namespace App\Http\Controllers\Regular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ModelHelper;
use App\Http\Controllers\Api\SignupAPI;
use App\Models\Role; // Ensure you have the Role model
use App\Models\Patient; // Ensure you have the Role model


class SignupController extends Controller
{
    public static function index()
    {
        $familyCode = ModelHelper::getRandomString();

        session(['familyCode' => $familyCode]);

        return view("signup")->with([
            "roles" => Role::all(),
            "familyCode" => $familyCode
        ]);
    }

    public static function store(Request $request)
    {
        // Call the SignupAPI to create the user
        $response = SignupAPI::store($request);

        // Check if the response status is 200 (OK)
        if ($response->getStatusCode() !== 200)
        {
            $errors = json_decode($response->getContent(), true)["errors"] ?? ["Invalid input(s). Please try again."];

            return redirect()->back()->withErrors($errors);
        }

        // Forget the family code stored in the session
        session()->forget("familyCode");

        // Flash a success message
        session()->flash("success", "Your account has been created successfully. Please wait for approval.");

        return redirect("/login");

    //     // Get the role ID from the request
    //     $roleId = $request->input('role_id');
    //     $role = Role::find($roleId);

    //     // If the role doesn't exist, redirect to the login form with an error
    //     if (!$role) {
    //         return redirect()->route("login.form")->withErrors("Invalid role selected.");
    //     }

    //     // Get the newly created user
    //     $user = Auth::user(); // This assumes you're using Auth for authentication

    //     // Create a new record for the appropriate model based on role
    //     if ($role->access_level == 5) { // For example, if it's a patient
    //         $patient = new Patient();
    //         $patient->user_id = $user->id;
    //         $patient->approved = false; // Set as unapproved until it's manually approved
    //         $patient->save();
    //     } else {
    //         // You can add other conditions for different roles (e.g. employee, doctor)
    //         // For now, I will assume other roles don't need a separate record, just approval status
    //     }

    //     // Redirect the user to the registration approval page (admin approval)
    //     return redirect()->route('registrationapproval.index');
    }
}
