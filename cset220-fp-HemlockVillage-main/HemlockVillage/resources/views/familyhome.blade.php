<html>
    <head>
        <title>Family Home</title>
		<link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">
        <style>
            .container{
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Family Home</h1>

            <form action="/home" method="get">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" readonly
                        value="{{ $date ?? '' }}"
                    >
                </div>

                <div class="form-group">
                    <label for="family-code">Family Code:</label>
                    <input type="text" id="family-code" name="family_code" placeholder="Enter Family Code" maxlength="16" required
                        value="{{ session('familyCode') ?? '' }}"
                    >

                    {{-- Error --}}
                    @error("family_code") <div>{{ $message }}</div> @enderror

                </div>

                <div class="form-group">
                    <label for="patient-id">Patient ID:</label>
                    <input type="text" id="patient-id" name="patient_id" placeholder="Enter Patient ID" maxlength="16" required
                    value="{{ session('patientId') ?? '' }}"
                    >

                    {{-- Error --}}
                    @error("patient_id") <div>{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <button type="submit">Search for Patient</button>
                </div>
            </form>

            @isset($data)
                <h2>Patient Information</h2>

                {{-- Appointment and Caregiver --}}
                <table>
                    <thead>
                        <tr>
                            <th>Doctor's Name</th>
                            <th>Appointment Status</th>
                            <th>Caregiver's Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ $data["doctor_name"] ?? "None"}}</td>
                            <td>{{ $data["appointment_status"] ?? "None"}}</td>
                            <td>{{ $data["caregiver_name"] ?? "None"}}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Meds --}}
                <table>
                    <thead>
                        <tr>
                            <th>Morning Meds</th>
                            <th>Afternoon Meds</th>
                            <th>Night Meds</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            {{--  Morning --}}
                            <td>
                                <textarea name="morning_prescription" id="morning_prescription" cols="30" rows="2">{{ $data["prescriptions"]["morning"] ?? "None" }}</textarea>
                                <p>{{ $data["prescription_status"]["morning"] ?? "None" }}</p>
                            </td>

                            {{-- Afternoon --}}
                            <td>
                                <textarea name="afternoon_prescription" id="afternoon_prescription" cols="30" rows="2">{{ $data["prescriptions"]["afternoon"] ?? "None" }}</textarea>
                                <p>{{ $data["prescription_status"]["afternoon"] ?? "None" }}</p>
                            </td>

                            {{-- Night --}}
                            <td>
                                <textarea name="night_prescription" id="night_prescription" cols="30" rows="2">{{ $data["prescriptions"]["night"] ?? "None" }}</textarea>
                                <p>{{ $data["prescription_status"]["night"] ?? "None" }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Meals --}}
                <table>
                    <thead>
                        <tr>
                            <th>Breakfast</th>
                            <th>Lunch</th>
                            <th>Dinner</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ $data["meal_status"]["breakfast"] ?? "None" }}</td>
                            <td>{{ $data["meal_status"]["lunch"] ?? "None" }}</td>
                            <td>{{ $data["meal_status"]["dinner"] ?? "None" }}</td>
                        </tr>
                    </tbody>
                </table>
            @endisset
        </div>

        @include('navbar')

    </body>
</html>
