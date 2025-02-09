<html>
    <head>
        <title>Admin Report</title>
		<link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">

        <style>
            body{
                display: flex;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>Admin Report</h1>

            <form action="/report" method="get">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="{{ $date ?? \Carbon\Carbon::today()->toDateString() ?? '' }}">
                </div>

                <div class="form-group">
                    <button type="submit">Generate Report</button>
                </div>
            </form>

            <h2>Missed Patient Activity</h2>

            <table>
                <thead>
                    <tr>
                        <th>Patient's Name</th>
                        <th>Doctor's Name</th>
                        <th>Doctor's Appointment</th>
                        <th>Caregiver's Name</th>
                        <th>Morning Meds</th>
                        <th>Afternoon Meds</th>
                        <th>Night Meds</th>
                        <th>Breakfast</th>
                        <th>Lunch</th>
                        <th>Dinner</th>
                    </tr>
                </thead>

                @if($data->isEmpty())
                    No patients has missed any activity for today

                @else
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                {{-- Patient, doctor, caregiver --}}
                                <td>{{ $d["patient_name"] ?? "" }}</td>
                                <td>{{ $d["doctor_name"] ?? "None" }}</td>
                                <td>{{ $d["appointment_status"] ?? "None" }}</td>
                                <td>{{ $d["caregiver_name"] ?? "None" }}</td>

                                {{-- Meds --}}
                                <td>{{ $d["prescriptions_status"]["morning"] ?? "None" }}</td>
                                <td>{{ $d["prescriptions_status"]["afternoon"] ?? "None" }}</td>
                                <td>{{ $d["prescriptions_status"]["night"] ?? "None" }}</td>

                                {{-- Meals --}}
                                <td>{{ $d["meal_status"]["breakfast"] ?? "None" }}</td>
                                <td>{{ $d["meal_status"]["lunch"] ?? "None" }}</td>
                                <td>{{ $d["meal_status"]["dinner"] ?? "None" }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>

        @include('navbar')

    </body>
</html>
