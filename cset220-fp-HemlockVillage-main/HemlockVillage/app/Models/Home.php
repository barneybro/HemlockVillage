<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $accessLevel = $user->role->access_level ?? 'patient';

        return view('home', compact('accessLevel'));
    }
}




