<?php

namespace App\Http\Controllers\Regular;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        // $patients = collect();

        // // Search logic
        // if ($request->hasAny(['patient_id', 'user_id', 'name', 'DOB', 'emergency_contact', 'emergency_contact_name'])) {
        //     $query = Patient::with('user');

        //     // Search by patient ID
        //     if ($request->patient_id) {
        //         $query->where('id', $request->patient_id);
        //     }
        //     // Search by user ID
        //     if ($request->user_id) {
        //         $query->where('user_id', $request->user_id);
        //     }
        //     // Search by first name
        //     if ($request->name) {
        //         $query->whereHas('user', function ($q) use ($request) {
        //             $q->where('first_name', 'like', '%' . $request->name . '%');
        //         });
        //     }
        //     // Search by DOB
        //     if ($request->DOB) {
        //         $query->whereHas('user', function ($q) use ($request) {
        //             // Match the DOB exactly
        //             $q->whereDate('date_of_birth', $request->DOB);
        //         });
        //     }
        //     // Search by emergency contact
        //     if ($request->emergency_contact) {
        //         $query->where('econtact_phone', 'like', '%' . $request->emergency_contact . '%');
        //     }
        //     // Search by emergency contact name
        //     if ($request->emergency_contact_name) {
        //         $query->where('econtact_name', 'like', '%' . $request->emergency_contact_name . '%');
        //     }

        //     $patients = $query->get();
        // }

        $patients = Patient::all();

        return response()->json($patients->map(function ($patient) {
            return [
                'patient_id' => $patient->id,
                'user_id' => $patient->user->id,
                'name' => "{$patient->user->first_name} {$patient->user->last_name}" ?? '',
                'dob' => $patient->user->date_of_birth ?? '',
                'emergency_contact' => $patient->econtact_phone,
                'emergency_contact_name' => $patient->econtact_name,
                'age' => $patient->user->date_of_birth
                    ? Carbon::parse($patient->user->date_of_birth)->age
                    : null,
            ];
        }));
    }

    // Show patient details and include form for updating
    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patientinfo', compact('patient'));
    }

    // Method to approve the registration and set the admission date
    public function approveRegistration(Request $request, $patientId)
    {
        $patient = Patient::find($patientId);

        if (!$patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $patient->approved = true;
        $patient->admission_date = now(); // Set the admission date to current date
        $patient->save();

        return redirect()->route('patients.index')->with('success', 'Patient approved successfully.');
    }

    // Method to update the group number
    public function updateGroupNumber(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $request->validate([
            'group_num' => 'required|integer|between:1,4', // Group number should be between 1 and 4
        ]);

        $patient->group_num = $request->input('group_num');
        $patient->save();

        return redirect()->route('patients.show', ['id' => $patientId])
            ->with('success', 'Group number updated successfully.');
    }

    // Method to update the emergency contact
    public function updateEmergencyContact(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $request->validate([
            'emergency_contact' => 'required|string|max:255',
        ]);

        $patient->emergency_contact = $request->input('emergency_contact');
        $patient->save();

        return redirect()->route('patients.show', ['id' => $patientId])
            ->with('success', 'Emergency contact updated successfully.');
    }

    // Fetch and return all patients (JSON response for API or other needs)
    public function getPatients(Request $request)
    {
        $patients = Patient::with('user')->get();

        return response()->json($patients);
    }

    // This method handles patient search functionality with filters
    public function search(Request $request)
{
    $query = Patient::with('user'); // Load user data associated with the patient

    // Add filters based on the request
    if ($request->patient_id) {
        $query->where('id', $request->patient_id);
    }
    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }
    if ($request->name) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->name . '%');
        });
    }
    if ($request->age) {
        // Calculate the start and end range for the given age
        $startDate = now()->subYears($request->age + 1)->addDay();
        $endDate = now()->subYears($request->age);

        $query->whereHas('user', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('date_of_birth', [$startDate, $endDate]);
        });
    }
    if ($request->emergency_contact) {
        $query->where('econtact_phone', 'like', '%' . $request->emergency_contact . '%');
    }
    if ($request->emergency_contact_name) {
        $query->where('econtact_name', 'like', '%' . $request->emergency_contact_name . '%');
    }

    $patients = $query->get();

    // Return filtered patients in JSON format
    return response()->json($patients->map(function ($patient) {
        return [
            'patient_id' => $patient->id,
            'user_id' => $patient->user->id,
            'name' => "{$patient->user->first_name} {$patient->user->last_name}",
            'age' => $patient->user->date_of_birth
                ? now()->diffInYears($patient->user->date_of_birth) // Calculate age
                : null,
            'emergency_contact' => $patient->econtact_phone,
            'emergency_contact_name' => $patient->econtact_name,
        ];
    }));
}


    // Fetch all unapproved patients
    public function getUnapprovedPatients()
    {
        $patients = Patient::where('approved', false)->with('user')->get();

        return response()->json($patients);
    }
}
