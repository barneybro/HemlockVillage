<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class UserAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index()
    {
        return DB::table("users")
            ->join("roles", "users.role_id", "roles.id")
            ->select("users.id as id", "users.first_name", "users.last_name", "users.email", "users.phone_number", "users.date_of_birth", "roles.role as role")
            ->where("approved", 1)
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
