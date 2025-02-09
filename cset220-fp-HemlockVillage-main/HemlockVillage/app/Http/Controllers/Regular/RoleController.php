<?php

namespace App\Http\Controllers\Regular;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:30|unique:roles,name',
            'access_level' => 'required|integer|min:1|max:10',
        ]);

        $role = new Role();
        $role->name = $request->role_name;
        $role->access_level = $request->access_level;
        $role->save();

        return redirect()->route('rolecreation.index')->with('success', 'Role created successfully!');
    }

    public function store(Request $request)
{
    $request->validate([
        'role_name' => 'required|string|max:255|unique:roles,role', // Make sure the role name is unique
        'access_level' => 'required|integer|min:1|max:10', // Adjust as needed
    ]);

    Role::create([
        'role' => $request->input('role_name'),
        'access_level' => $request->input('access_level'),
    ]);

    return redirect()->route('rolecreation.index')->with('success', 'Role created successfully!');
}

    public function getRoles()
        {
            $roles = Role::all();
            return response()->json($roles);
        }

        public function createRole(Request $request)
        {
            $validated = $request->validate([
                'role_name' => 'required|string|max:255',
                'access_level' => 'required|integer|min:1|max:10', 
            ]);

            $role = new Role();
            $role->role = $validated['role_name'];
            $role->access_level = $validated['access_level'];
            $role->save();

            return response()->json(['message' => 'Role created successfully!']);
        }


}
