<html>
    <head>
        <title>Patients Home</title>

        <link rel="stylesheet" href="{{ asset('./css/patientshome.css') }}">

        <script src="{{ asset('./js/script.js') }}"></script>
    </head>

    <body>

        {{-- @extends('layouts.app')

        @section('content') --}}


        <div class="container">
            <h1>Patients Home</h1>

            <div class="flexbox">
                <label>Patient ID</label>
                <input type="text" id="patient-id" name="patient_id" readonly
                    value="{{ $data['patient_id'] ?? '' }}"
                >
            </div>

            <div class="flexbox">
                <label>Patient Name</label>
                <input type="text" id="patient-name" name="patient_name" readonly
                    value="{{ $data['patient_name'] ?? '' }}"
                >
            </div>

            {{-- Date form --}}
            <div class="flexbox">
                <form method="get"
                    onsubmit="submitHomePageWithDate(event)"
                >
                    <label>Date</label>
                    <input type="date" id="date" name="date"
                        value="{{ $data['date'] ?? '' }}"
                        max="{{ \Carbon\Carbon::today()->toDateString() }}" {{-- Up to current date only --}}
                    >

                    <button type="submit">Submit</button>
                </form>
            </div>

            {{-- Patient info specific to a date --}}
            <div class="patient-info">
                <h3>Appointments and Caregivers</h3>
                <table>
                    <tr>
                        <th>Doctor's Name</th>
                        <th>Appointment Status</th>
                        <th>Caregiver's Name</th>
                    </tr>
                    <tr>
                        <td>{{ $data["doctor_name"] ?? "None" }}</td>
                        <td>{{ $data["appointment_status"] ?? "None" }}</td>
                        <td>{{ $data["caregiver_name"] ?? "None" }}</td>
                    </tr>
                </table>

                <h3>Medicine</h3>
                <table>
                    <tr>
                        <th>Morning</th>
                        <th>Afternoon</th>
                        <th>Night</th>
                    </tr>
                    <tr>
                        <td>{{ $data["prescription_status"]["morning"] ?? "None" }}</td>
                        <td>{{ $data["prescription_status"]["afternoon"] ?? "None" }}</td>
                        <td>{{ $data["prescription_status"]["night"] ?? "None" }}</td>
                    </tr>
                </table>

                <h3>Meals</h3>
                <table>
                    <tr>
                        <th>Breakfast</th>
                        <th>Lunch</th>
                        <th>Dinner</th>
                    </tr>
                    <tr>
                        <td>{{ $data["meal_status"]["breakfast"] ?? "None" }}</td>
                        <td>{{ $data["meal_status"]["lunch"] ?? "None" }}</td>
                        <td>{{ $data["meal_status"]["dinner"] ?? "None" }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @include('navbar')

    </body>

</html>
