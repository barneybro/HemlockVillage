<?php

namespace App\Http\Controllers\Regular; // This defines the namespace of your controllers

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckRole;

require("rapi.php");

Route::get("/", fn() => PageController::landing());

// ======== Authentication Routes ========
Route::get("/signup", fn() => SignupController::index());
Route::post("/signup", fn() => SignupController::store(request()));

Route::get("/login", fn() => LoginController::showLoginForm())->name("login.form");
Route::post("/login", fn() => LoginController::login(request()));

Route::get("/logout", fn() => LoginController::logout(request()))->name("logout");

// ======== Admin and Supervisor Access Routes ========
Route::middleware([CheckRole::class . ':1,2'])->group(function () {
    Route::get("/users", fn() => PageController::users());

    // Report
    Route::get("/report", fn() => PageController::report());

    // Roster
    // Not a requirement, and don't have time for this
    // Route::get('/rosters', [RosterController::class, 'index'])->name('rosters.index'); // Roster overview
    Route::get("/roster/create", fn() => PageController::indexrosterForm());
    Route::post("/roster/create", fn() => PageController::storeRosterForm(request()));

    // Appointment scheduling
    Route::get("/schedule", fn() => PageController::indexSchedule());
    Route::post("/schedule", fn() => PageController::storeSchedule(request()));

    // Employee search
    Route::get('/searchemployee', fn() => view('searchemployee'))->name('employeesearch'); // Search form
    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search'); // Search logic fetch API
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employeeinfo.index'); // Employee list

    // Employee Info
    Route::get('/employeeinfo/{id}', [EmployeeController::class, 'show'])->name('employeeinfo.show'); // Employee details

    // Patient info updating group num
    Route::post('/patients/{patientId}/update-group-num', [PatientController::class, 'updateGroupNumber'])->name('patients.updateGroupNumber');

    // Rgistration approval
    Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])->name('registrationapproval.index');
    Route::post('/users/{userId}/approve', [RegistrationApprovalController::class, 'approve'])->name('users.approve');
    Route::post('/users/{userId}/reject', [RegistrationApprovalController::class, 'reject'])->name('users.reject');
});

// ======== Admin Access Routes ========
Route::middleware([CheckRole::class . ":1"])->group(function ()
{
    // Payment / Bill
    Route::get("/payment", fn() => PageController::indexPayment());
    Route::get("/payment/{patientId}", fn($patientId) => PageController::showPayment($patientId));
    Route::patch("/payment/{patientId}", fn($patientId) => PageController::updatePayment(request(), $patientId));

    // Role Creation
    Route::get('/rolecreation', fn() => view('rolecreation'))->name('rolecreation.index');
    Route::post('/create-role', [RoleController::class, 'createRole'])->name('rolecreation.create');
    Route::get('/roles', [RoleController::class, 'getRoles'])->name('roles.fetch'); // Fetch API

    // Update salary
    Route::post('/employees/{employeeId}/update-salary', [EmployeeController::class, 'updateSalary'])->name('employees.updateSalary');
});

// ======== All Authenticated Users Routes ========
Route::middleware("auth")->group(function ()
{
    // Home
    Route::get("/home", fn() => PageController::home());

     // Roster
     Route::get("/roster", fn() => PageController::showRoster())->name("roster.show");
});

// ======== Everyone but patient and family Access Routes ========
Route::middleware([CheckRole::class . ":1,2,3,4"])->group(function ()
{
    // Search
    Route::get("/search/patients", fn() => PageController::searchPatients(request()));
    Route::get('/patients', [PatientController::class, 'index'])->name('patientinfo.index'); // Patient list

    // Patient info
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show'); // View patient info
});

// ======== Doctor and Patient Access ========
Route::middleware([ CheckRole::class . ":3,5"])->group( function ()
{
    // Home
    Route::get("/home/{date}", fn($date) => PageController::homeWithDate($date));
});

// ======== Doctor Access ========
Route::middleware([ CheckRole::class . ":3"])->group( function ()
{
    Route::get("/doctor/patients/{patientId}", fn($patientId) => PageController::showDoctorPatient($patientId));
    Route::patch("/doctor/patients/{patientId}", fn($patientId) => PageController::updateDoctorPatient(request(), $patientId));

    Route::get("/doctor/missing/{appointmentId}", fn($appointmentId) => PageController::updateMissingAppointment(request(), $appointmentId));
});

// ======== Caregiver Access ========
Route::middleware([ CheckRole::class . ":4"])->group( function ()
{
    Route::put("/caregiver/update/{patientId}", fn($patientId) => PageController::updateCaregiverHome(request(), $patientId));
});
// ======== Employee Routes ========
// ***** Undefined variable $employee.
// ***** Property [user] does not exist on this collection instance. This is because you are not looping through all the employees in the blade file so it is trying to find a key called `user` in the INDEX array
// Route::get('/employees', [EmployeeController::class, 'index'])->name('employeeinfo.index'); // Employee list

// Interesting... ideally, you would have the same route names for GET with search parameters (the things after the `?` in the url) since it should be the same page, hence the same url
// Don't bother rn. need to get back end done ASAP
// Route::get('/searchemployee', fn() => view('searchemployee'))->name('employeesearch'); // Search form
// Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search'); // Search logic
// Route::get('/employeeinfo/{id}', [EmployeeController::class, 'show'])->name('employeeinfo.show'); // Employee details

// Since these bring up the same page, just name the routes `/employees/{employeeId}`.
// If you do change it, make sure to change the fetch API url or form action, whichever you were using
// Route::post('/employees/{employeeId}/update-salary', [EmployeeController::class, 'updateSalary'])->name('employees.updateSalary');

// ======== Patient Routes ========
// It appears that you are trying to search in this function... so you get nothing if there are no query parameters. there is no view being returned
// you can maybe change `$patients = collect();` to `$patients = Patients::with("users");`. this will then appear to be the same function as your searching either way.
// I didn't check how you coded and connected the code to the view, if it was through JS with fetch API, so double check this
// what you can do is change `/patients/search` to `/patients` and change this function from `index` to `search`. Make sure to change the url for fetch API if you do change
// Route::get('/patients', [PatientController::class, 'index'])->name('patientinfo.index'); // Patient list
// Route::post('/patients/{patientId}/update-group-num', [PatientController::class, 'updateGroupNumber'])->name('patients.updateGroupNumber');

// this seems to get the data for all patients at least, and then applies the searching. this means that this will dispaly all the patients, even if there are no query parameters
// Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search'); // Search patient

// Nice. I did not connect the two together that you can just return the patients table and use the relation functions to access values
// I did fix the incorrect column names in the blade file so the name, role, and emergency contact name shows up
// Also added some other info
// Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show'); // View patient info

// I think this was what you were thinking with the updating patient stuff.
// Should allow updating of admission date and group num. It is not the emergency contact info
// Route::post('/patients/{patientId}/updateEmergencyContact', [PatientController::class, 'updateEmergencyContact'])->name('patients.updateEmergencyContact'); // Update emergency contact

// Route::post('/patients/{patientId}/approve', [PatientController::class, 'approveRegistration'])->name('patients.approve'); // Approve patient registration

// ======== Registration Approval Routes ========

// Routes for approving or rejecting patients
// Route::post('/patients/{patientId}/approve', [RegistrationApprovalController::class, 'approvePatient'])->name('patients.approve');
// Route::post('/patients/{patientId}/reject', [RegistrationApprovalController::class, 'rejectPatient'])->name('patients.reject');

// Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])->name('registrationapproval.index');

// Route::post('/users/{userId}/approve', [RegistrationApprovalController::class, 'approve'])->name('users.approve');
// Route::post('/users/{userId}/reject', [RegistrationApprovalController::class, 'reject'])->name('users.reject');

// // ======== Role Creation ==========
// Route::get('/rolecreation', fn() => view('rolecreation'))->name('rolecreation.index');

// Route::post('/create-role', [RoleController::class, 'createRole'])->name('rolecreation.create');

// Route::get('/roles', [RoleController::class, 'getRoles'])->name('roles.fetch');




// ======== Roster Routes ========
// Route::get('/rosters', [RosterController::class, 'index'])->name('rosters.index'); // Roster overview
// Route::get('/rosters/view', [RosterController::class, 'viewRoster'])->name('rosters.view'); // View roster

// ======== Doctor Routes ========
// /home is the home page for ALL users. The page will load the correct data for each type of access level
// Route::get('/doctorshome', [DoctorController::class, 'index'])->name('doctorshome.index'); // Doctor's homepage

// Route::get('/patientofdoc', [DoctorController::class, 'patients'])->name('patientofdoc.index'); // Patient list for doctor

// What is this? shows the same page as /patientofdoc. Again, /home for all users
// Route::get('/caregivershome', [DoctorController::class, 'patients'])->name('caregivershome.index'); // Caregiver's homepage

// ======== Miscellaneous Routes ========
// Route::get('/searchpatient', fn() => view('searchpatient'))->name('patientsearch');






















// Route::get('/patientshome', function () {
//     return view('patientshome');
// });

// Route::get('/patientinfo', function () {
//     return view('patientinfo');
// });

// Route::get('/doctorsappointment', function () {
//     return view('doctorsappointment');
// });



// Route::get('/employeeinfo', function () {
//     return view('employeeinfo');
// });

// Route::get('/rolecreation', function () {
//     return view('rolecreation');
// });

// Route::get('/editroles', function () {
//     return view('editroles');
// });



// Route::get('/registrationapproval', function () {
//     return view('registrationapproval');
// });

// Route::get('/newroster', function () {
//     return view('newroster');
// });

// Route::get('/rosters', function () {
//     return view('rosters');
// });

// Route::get('/editroster', function () {
//     return view('editroster');
// });

// Route::get('/payments', function () {
//     return view('payments');
// });

// Route::get('/familyhome', function () {
//     return view('familyhome');
// });

// Route::get('/adminreport', function () {
//     return view('adminreport');
// });

// Route::get('/createprescription', function () {
//     return view('createprescription');
// });

// Route::get('/patientofdoc', function () {
//     return view('patientofdoc');
// });

// Route::get('/caregivershome', function () {
//     return view('caregivershome');
// });

// Route::get('/familypayment', function () {
//     return view('familypayment');
// });

// Route::get('/searchemployee', function () {
//     return view('searchemployee');
// });
