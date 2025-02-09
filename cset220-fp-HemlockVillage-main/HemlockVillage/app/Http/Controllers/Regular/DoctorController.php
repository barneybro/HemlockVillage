<?php

namespace App\Http\Controllers\Regular;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return view('doctorshome');
    }

    public function patients()
    {
        return view('patientofdoc');
    }
}
