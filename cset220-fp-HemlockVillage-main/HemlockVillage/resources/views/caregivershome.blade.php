<html>
    <head>
        <title>Caregiver's Home</title>
		<link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">

        <script src="{{ asset("./js/navigator.js") }}"></script>
        <style>
            .container{
                margin-top: 50px;
            }
        </style>
    </head>

    <body>
        {{-- @extends('layouts.app')

        @section('content') --}}

        @php
            $authUser = Auth::user();
            $caregiverId = $authUser->employees->first()->id ?? null;
            $caregiverName = "{$authUser->first_name} {$authUser->last_name}" ?? null;
        @endphp

        <div class="container">
            <h1>Caregiver's Home</h1>

            <div class="form-group">
                <label for="caregiver-id">Caregiver ID:</label>
                <input type="text" id="caregiver-id" value="{{ $caregiverId ?? '' }}" readonly>
            </div>

            <div class="form-group">
                <label for="caregiver-id">Name:</label>
                <input type="text" id="caregiver-id" value="{{ $caregiverName ?? ''}}" readonly>
            </div>


            <div class="form-group">
                <label for="date">Date:</label>
                <input type="text" id="date" value="{{ $date ?? ''}}" readonly>
            </div>

            <div class="form-group">
                <label for="groupNum">Group Number:</label>
                <input type="text" id="groupNum" value="{{ $groupNum ?? ''}}" readonly>
            </div>


            {{-- Error from PUT --}}
            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Message from PUT --}}
            @if (session('message'))
                <div>
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            {{-- No roster created or not on roster --}}
            @if(isset($message))
                <div>{{ $message }}</div>

            {{-- Display patients --}}
            @else
                <h2>List of Patients Today</h2>

                @isset($data)
                {{-- No patients --}}
                    @if(empty($data))
                        No patients

                    {{-- Display patients --}}
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Meds</th>
                                    <th>Meals</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data as $d)
                                    <form action="/caregiver/update/{{ $d['patient_id'] }}" method="post">
                                        @method("put")
                                        @csrf


                                        <tr>
                                            <td>{{ $d["patient_name"] }}

                                                {{-- Ids --}}
                                                <input type="number" name="prescription_status_id" value="{{ $d['prescription_status_id'] }}" readonly hidden>
                                                <input type="number" name="meal_id" value="{{ $d['meal_id'] }}" readonly hidden>
                                            </td>

                                            {{-- Meds --}}
                                            <td>
                                                {{-- Morning --}}
                                                <label for="">Morning</label>
                                                <textarea name="" id="" cols="5" rows="1">{{ $d["prescriptions"]["morning"] ?? 'None' }}</textarea>

                                                @if($d["prescriptions"]["morning"])
                                                    <select name="morning_med" id="" required>
                                                        <option value="Missing" @selected($d["prescription_status"]["morning"] === "Missing")>Missing</option>
                                                        <option value="Pending" @selected($d["prescription_status"]["morning"] === "Pending")>Pending</option>
                                                        <option value="Completed" @selected($d["prescription_status"]["morning"] === "Completed")>Completed</option>
                                                    </select>
                                                @endif

                                                {{-- Afternoon --}}
                                                    <label for="">Afternoon</label>
                                                    <textarea name="" id="" cols="5" rows="1">{{ $d["prescriptions"]["afternoon"] ?? 'None' }}</textarea>

                                                    @if($d["prescriptions"]["afternoon"])
                                                        <select name="afternoon_med" id="" required>
                                                            <option value="Missing" @selected($d["prescription_status"]["afternoon"] === "Missing")>Missing</option>
                                                            <option value="Pending" @selected($d["prescription_status"]["afternoon"] === "Pending")>Pending</option>
                                                            <option value="Completed" @selected($d["prescription_status"]["afternoon"] === "Completed")>Completed</option>
                                                        </select>
                                                    @endif

                                                    {{-- Night --}}
                                                    <label for="">Night</label>
                                                    <textarea name="" id="" cols="5" rows="1">{{ $d["prescriptions"]["night"] ?? 'None' }}</textarea>

                                                    @if($d["prescriptions"]["night"])
                                                        <select name="night_med" id="" required>
                                                            <option value="Missing" @selected($d["prescription_status"]["night"] === "Missing")>Missing</option>
                                                            <option value="Pending" @selected($d["prescription_status"]["night"] === "Pending")>Pending</option>
                                                            <option value="Completed" @selected($d["prescription_status"]["night"] === "Completed")>Completed</option>
                                                        </select>
                                                    @endif

                                            </td>

                                            {{-- Meals --}}
                                            <td>
                                                {{-- Breakfast --}}
                                                <div>
                                                    <label for="">Breakfast</label>

                                                    {{-- No status --}}
                                                    @if(!$d["meal_status"]["breakfast"])
                                                        None

                                                    {{-- Status exists --}}
                                                    @else
                                                        <select name="breakfast" id="" required>
                                                            <option value="Missing" @selected($d["meal_status"]["breakfast"] === "Missing")>Missing</option>
                                                            <option value="Pending" @selected($d["meal_status"]["breakfast"] === "Pending")>Pending</option>
                                                            <option value="Completed" @selected($d["meal_status"]["breakfast"] === "Completed")>Completed</option>
                                                        </select>
                                                    @endif
                                                </div>

                                                {{-- Lunch --}}
                                                <div>
                                                    <label for="">Lunch</label>

                                                    {{-- No status --}}
                                                    @if(!$d["meal_status"]["lunch"])
                                                        None
                                                    {{-- Status exists --}}
                                                    @else
                                                        <select name="lunch" id="" required>
                                                            <option value="Missing" @selected($d["meal_status"]["lunch"] === "Missing")>Missing</option>
                                                            <option value="Pending" @selected($d["meal_status"]["lunch"] === "Pending")>Pending</option>
                                                            <option value="Completed" @selected($d["meal_status"]["lunch"] === "Completed")>Completed</option>
                                                        </select>
                                                    @endif
                                                </div>

                                                {{-- Dinner --}}
                                                <div>
                                                    <label for="">Dinner</label>

                                                    {{-- No status --}}
                                                    @if(!$d["meal_status"]["dinner"])
                                                        None
                                                    {{-- Status exists --}}
                                                    @else
                                                        <select name="dinner" id="" required>
                                                            <option value="Missing" @selected($d["meal_status"]["dinner"] === "Missing")>Missing</option>
                                                            <option value="Pending" @selected($d["meal_status"]["dinner"] === "Pending")>Pending</option>
                                                            <option value="Completed" @selected($d["meal_status"]["dinner"] === "Completed")>Completed</option>
                                                        </select>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Action buttons --}}
                                            <td>
                                                <button type="submit">Update</button>
                                                <button type="button" onclick="setTop(`/patients/{{ $d['patient_id'] }}`)" >View Patient</button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        @endif
                    @endisset
                </table>
            @endif
        </div>

        @include('navbar')

    </body>
</html>
