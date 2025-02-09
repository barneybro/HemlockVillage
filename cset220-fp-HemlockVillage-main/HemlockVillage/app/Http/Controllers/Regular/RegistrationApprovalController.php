<?php
namespace App\Http\Controllers\Regular;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Patient;
use App\Models\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class RegistrationApprovalController extends Controller
{
    public function index()
    {
        // Fetch all users who need approval
        $users = DB::table('users')
            ->where('approved', false)
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.role')
            ->get();

        return view('registrationapproval', compact('users'));
    }

    // Approve user
    public function approve(Request $request, $userID)
    {
        $user = User::find($userID);
        if (!$user) {
            return redirect()->route('registrationapproval.index')->with('error', 'User not found');
        }

        // Create employee if employee role
        if (in_array($user->role_id, [ 1, 2, 3, 4 ]))
            Employee::create([ "user_id" => $user->id ]);

        /**
         *  Patient
         */
        $patient = Patient::where("user_id", $user->id)->first();

        if ($patient)
        {
            $patient->update([
                "admission_date" => Carbon::today()->toDateString(),
                "daily_updated_date" => Carbon::today()->toDateString(),
                "prescription_updated_date" => Carbon::today()->toDateString()
            ]);
        }

        /**
         * Change approved status
         */
        $user->approved = true; // Set the user as approved
        $user->save();

        return redirect()->route('registrationapproval.index')->with('success', 'User approved successfully.');
    }

    // Reject user
    public function reject(Request $request, $userID)
    {
        $user = User::find($userID);
        if (!$user) {
            return redirect()->route('registrationapproval.index')->with('error', 'User not found');
        }

         /**
         * Delete from patients table if needed
         */
        $patient = Patient::where("user_id", $user->id)->first();

        if ($patient) $patient->delete(); // Patient found, delete

        $user->delete(); // Remove the user from the database

        return redirect()->route('registrationapproval.index')->with('success', 'User rejected and removed successfully.');
    }
}
