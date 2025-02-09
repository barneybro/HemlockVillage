<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information</title>

    <link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">
</head>
<body>
    <div class="container">
        <h1>Patient Information</h1>

        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date of Birth</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->user->first_name }} {{ $patient->user->last_name }}</td>
                    <td>{{ $patient->user->email }}</td>
                    <td>{{ $patient->user->role->role }}</td>
                    <td>{{ $patient->user->date_of_birth }}</td>
                    <td>{{ $patient->user->phone_number }}</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th>Family Code</th>
                    <th>Emergency Contact</th>
                    <th>Emergency Contact Phone</th>
                    <th>Relation</th>
                    <th>Admission Date</th>
                    <th>Group Number</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $patient->family_code }}</td>
                    <td>{{ $patient->econtact_name }}</td>
                    <td>{{ $patient->econtact_phone }}</td>
                    <td>{{ $patient->econtact_relation }}</td>
                    <td>{{ $patient->admission_date }}</td>
                    <td>{{ $patient->group_num }}</td>
                </tr>
            </tbody>
        </table>

        @php
            $accessLevel = Auth::user()->role->access_level ?? null;
        @endphp

        {{-- Display only if admin or supervisor --}}
        @if($accessLevel === 1 || $accessLevel === 2)
            <div class="form-section">
                <h2>Update Group Number</h2>
                <form action="{{ route('patients.updateGroupNumber', $patient->id) }}" method="POST">
                    @csrf
                    <label for="group_num">Group Number (1-4):</label>
                    <input type="number" id="group_num" name="group_num" value="{{ $patient->group_num }}" min="1" max="4" required>
                    <button type="submit">Update Group Number</button>
                </form>
            </div>
        @endif

    </div>
    @include('navbar')

</body>
</html>
