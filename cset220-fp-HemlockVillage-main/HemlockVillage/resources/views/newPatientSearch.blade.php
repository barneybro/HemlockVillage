<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>New Search Patients</title>

        <link rel="stylesheet" href="{{ asset('./css/searchpatient.css') }}">
    </head>
    <body>
        <div class="container">
            <h1>Search Patients</h1>

            <div class="search-bar">
                <form id="search-form" action="/search/patients" method="get">
                   {{-- User id --}}
                    <div>
						<label for="user-id">Search by User ID</label>
						<input type="number" name="user_id" id="user-id" placeholder="Enter User ID">
					</div>

                    {{-- Patient id --}}
                    <div>
                        <label for="patient-id">Search by Patient ID</label>
                        <input type="text" name="patient_id" id="patient-id" placeholder="Patient ID" maxlength="16">
                    </div>

                    {{-- Name --}}
                    <div>
						<label for="name">Search by Name</label>
						<input type="text" name="name" id="name" placeholder="Enter Name" maxlength="100">
					</div>

                    {{-- Age --}}
                    <div>
						<label for="age">Search by Age</label>
						<input type="number" name="age" id="age" placeholder="Enter Age">
					</div>

                    {{-- E-contact phone --}}
                    <div>
						<label for="emergency-contact">Search by Emergency Contact</label>
						<input type="text" name="emergency_contact" id="emergency-contact" placeholder="Enter Emergency Contact" maxlength="20">
					</div>

                    {{-- E-contact name --}}
                    <div>
						<label for="emergency-contact-name">Search by Emergency Contact Name</label>
						<input type="text" name="emergency_contact_name" id="emergency-contact-name" placeholder="Enter Emergency Contact Name" maxlength="20">
					</div>

					<button type="submit">Search</button>
                </form>
            </div>

            <div id="patient-list">
                @isset($data)
                    @if(empty($data))
                        No patients found
                    @else
                        @foreach($data as $p)
                            <div class="patient-card">
                                <h3>Patient Name: {{ $p["name"] }}</h3>
                                <p>Patient ID: {{ $p["patient_id"] }}</p>
                                <p>User ID: {{ $p["user_id"] }}</p>
                                <p>DOB: {{ $p["date_of_birth"] }}</p>
                                <p>Emergency Contact: {{ $p["emergency_contact"] }}</p>
                                <p>Emergency Contact Name: {{ $p["emergency_contact_name"] }}</p>
                                <button onclick="window.location.href=`/patients/{{ $p['patient_id'] }}`">View Patient Info</button>
                            </div>
                        @endforeach
                    @endif
                @endisset
            </div>
        </div>

        @include("navbar")
    </body>
</html>
