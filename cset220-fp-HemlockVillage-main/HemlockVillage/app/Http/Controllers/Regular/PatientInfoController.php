<?php

namespace App\Http\Controllers\Regular;
use App\Http\Controllers\Controller; 
use App\Models\Patient; 

use Illuminate\Http\Request;

class PatientInfoController extends Controller

{
    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patientinfo', compact('patient'));
    }
}


