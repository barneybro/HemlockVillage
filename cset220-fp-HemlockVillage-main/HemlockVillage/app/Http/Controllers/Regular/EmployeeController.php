<?php

namespace App\Http\Controllers\Regular;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
{
    $employees = Employee::with('user')->get(); // Fetch employees with related user info

    // return $employees;

    // Pass the data to the view
    return view('employeeinfo', compact('employees'));
}

public function updateSalary(Request $request, $id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    $request->validate([
        'new_salary' => 'required|numeric|min:0',
    ]);

    $employee->salary = $request->new_salary;
    $employee->save();

    return response()->json(['message' => 'Salary updated successfully', 'new_salary' => $employee->salary]);
}




public function search(Request $request)
{
    $query = Employee::with('user'); // Load user data associated with the employee

    // Filtering by the fields
    if ($request->employee_id) {
        $query->where('id', $request->employee_id);
    }
    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }
    if ($request->name) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->name . '%');
        });
    }
    if ($request->role) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('role_id', $request->role); // Filter by role_id
        });
    }
    if ($request->salary) {
        $query->where('salary', $request->salary);
    }

    // Execute query and get results
    $employees = $query->get();

    // Return filtered employees in JSON format
    return response()->json($employees->map(function ($employee) {
        return [
            'employee_id' => $employee->id,
            'user_id' => $employee->user->id,
            'name' => "{$employee->user->first_name} {$employee->user->last_name}",
            'role' => $employee->user->role->role, // Include role name from the `roles` table
            'salary' => $employee->salary,
        ];
    }));
}







    public function show($id)
    {
        $employee = Employee::with(['user', 'user.role'])->find($id);

        if (!$employee) {
            return redirect()->route('employeeinfo.index')->with('error', 'Employee not found');
        }

        return view('employeeinfo', compact('employee'));
    }


}
