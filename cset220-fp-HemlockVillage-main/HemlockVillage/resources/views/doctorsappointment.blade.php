<html>
    <head>
        <title>Doctors Appointment Scheduling</title>
		<link rel="stylesheet" href="{{ asset('./css/doctorsappointments.css') }}">

        <script src="{{ asset('./js/navigator.js') }}"></script>
        <script src="{{ asset('./js/appointmentSchedule.js') }}"></script>
    </head>

    <body>
        <div class="container">
            <h1>Doctors Appointment Scheduling</h1>

            {{-- POST Success --}}
            @if(session("message"))
                <div>{{ session("message") }}</div>
            @endif

            {{-- Date and patient id --}}
            <form id="appointment-form" method="post" action="/schedule">
                @csrf

                {{-- Appointment date --}}
                <div class="flexbox">
                    <label>Appointment Date</label>
                    <input type="date" id="appointment-date" name="appointment_date" required
                        onchange="redirectToSchedule()"
                        value="{{ old('appointment_date') ?? session('appointment_date') ?? $appointmentDate ?? '' }}"
                    >

                    {{-- Error --}}
                    @error("appointment_date") <div>{{ $message }}</div> @enderror
                </div>

                {{-- Patient ID --}}
                <div class="flexbox">
                    <label>Patient ID</label>
                    <input type="text" id="patient-id" name="patient_id" placeholder="Enter Patient ID" required
                        maxlength="16"
                        onchange="redirectToSchedule()"
                        oninput="redirectToSchedule()"
                        value="{{ old('patient_id') ?? session('patient_id') ?? $patientId ?? '' }}"
                    >

                    @error("patient_id") <div>{{ $message }}</div> @enderror
                </div>

                {{-- Errors --}}
                @if ($errors->has("doctor")) <div>{{ $errors->first("doctor") }}</div> @endif
                @if ($errors->has("roster")) <div>{{ $errors->first("roster") }}</div> @endif
                @if ($errors->has("duplicate")) <div>{{ $errors->first("duplicate") }}</div> @endif

                {{--  --}}
                @isset($patientId)
                    <div class="flexbox">
                        Patient Name: {{ $patientName ?? 'Could not retrieve name'}}
                    </div>
                @endisset
                 {{-- <div class="flexbox">
                    <label>Patient Name</label>
                    <input type="text" id="patient-name" name="patient_name" placeholder="Enter Patient Name">
                </div> --}}

                @isset($doctorName, $doctorId)
                    <div class="flexbox">
                        <label>Doctor</label>

                        <select id="doctor" name="doctor" required>
                            <option value="" selected disabled>Choose a Doctor</option>
                            <option value="{{ $doctorId ?? '' }}" @selected(old("doctor") === $doctorId)>{{ $doctorName ?? '' }}</option>
                        </select>
                    </div>

                    <div class="flexbox">
                        <button type="submit">Schedule Appointment</button>
                    </div>
                @endisset
            </form>
        </div>

        @include('navbar')

    </body>
</html>
