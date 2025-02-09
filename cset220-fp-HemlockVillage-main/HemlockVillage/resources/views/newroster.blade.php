<html>
    <head>
        <title>Create New Roster</title>
        <link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">
    </head>

    <body>
        <div class="container">
            {{-- Success message for creation --}}
            @if (session('message'))
                <div>
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <h1>Create New Roster</h1>

            <form action="/roster/create" method="POST">
                @csrf

                {{--  Date--}}
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date"
                        @isset($currentDate) min="{{ $currentDate }}" @endisset
                        required
                        value="{{ old('date', '') }}"
                    >

                    {{-- Error for date --}}
                    @error('date') <div>{{ $message }}</div> @enderror
                </div>

                {{-- Supervisor --}}
                <div class="form-group">
                    <label for="supervisor">Supervisor:</label>
                    <select id="supervisor" name="supervisor" required>
                        <option disabled @selected(old('supervisor') === null) value> -- select an option -- </option>

                        @isset($employees["supervisors"] )
                            @foreach ($employees["supervisors"]  as $s)
                                @isset($s['employee_id'], $s["name"] )
                                    <option
                                        value="{{ $s['employee_id'] }}"
                                        @selected(old('supervisor') == $s['employee_id'])
                                    >
                                        {{ $s["name"] }}
                                    </option>
                                @endisset
                            @endforeach
                        @endisset
                    </select>

                    {{-- Error for supervisor --}}
                    @error("supervisor") <div>{{ $message }}</div> @enderror
                </div>

                {{-- Doctor --}}
                <div class="form-group">
                    <label for="doctor">Doctor:</label>
                    <select id="doctor" name="doctor" required>
                        <option disabled @selected(old('doctor') === null) value> -- select an option -- </option>

                        @isset($employees["doctors"] )
                            @foreach ($employees["doctors"]  as $d)
                                @isset($d['employee_id'], $d["name"] )

                                    <option
                                        value="{{ $d['employee_id'] }}"
                                        @selected(old('doctor') == $d['employee_id'])
                                    >
                                        {{ $d["name"] }}
                                    </option>
                                @endisset
                            @endforeach
                        @endisset
                    </select>

                    {{-- Error for doctor --}}
                    @error("doctor") <div>{{ $message }}</div> @enderror
                </div>

                {{-- Caregiver one --}}
                <div class="form-group">
                    <label for="caregiver_one">Caregiver 1:</label>
                    <select id="caregiver_one" name="caregivers[]" required>
                        <option disabled @selected(old('caregivers.0') === null) value> -- select an option -- </option>

                        @isset($employees["caregivers"] )
                            @foreach ($employees["caregivers"]  as $c)
                                @isset($c['employee_id'], $c["name"] )
                                    <option
                                        value="{{ $c['employee_id'] }}"
                                        @if(in_array($c['employee_id'], old('caregivers', [])))
                                            selected
                                        @endif
                                    >
                                        {{ $c["name"] }}
                                    </option>
                                @endisset
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Caregiver two --}}
                <div class="form-group">
                    <label for="caregiver_two">Caregiver 2:</label>
                    <select id="caregiver_two" name="caregivers[]" required>
                        <option disabled @selected(old('caregivers.1') === null) value> -- select an option -- </option>

                        @isset($employees["caregivers"] )
                            @foreach ($employees["caregivers"]  as $c)
                                @isset($c['employee_id'], $c["name"] )
                                    <option
                                        value="{{ $c['employee_id'] }}"
                                        {{ in_array($c['employee_id'], old('caregivers', [])) ? 'selected' : '' }}
                                    >
                                        {{ $c["name"] }}
                                    </option>
                                @endisset
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Caregiver three --}}
                <div class="form-group">
                    <label for="caregiver_three">Caregiver 3:</label>
                    <select id="caregiver_three" name="caregivers[]" required>
                        <option disabled @selected(old('caregivers.2') === null) value> -- select an option -- </option>

                        @isset($employees["caregivers"] )
                            @foreach ($employees["caregivers"]  as $c)
                                @isset($c['employee_id'], $c["name"] )
                                    <option
                                        value="{{ $c['employee_id'] }}"
                                        {{ in_array($c['employee_id'], old('caregivers', [])) ? 'selected' : '' }}
                                    >
                                        {{ $c["name"] }}
                                    </option>
                                @endisset
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Caregiver four --}}
                <div class="form-group">
                    <label for="caregiver_four">Caregiver 4:</label>
                    <select id="caregiver_four" name="caregivers[]" required>
                        <option disabled @selected(old('caregivers.3') === null) value> -- select an option -- </option>

                        @isset($employees["caregivers"] )
                            @foreach ($employees["caregivers"]  as $c)
                                @isset($c['employee_id'], $c["name"] )
                                    <option
                                        value="{{ $c['employee_id'] }}"
                                        {{ in_array($c['employee_id'], old('caregivers', [])) ? 'selected' : '' }}
                                    >
                                        {{ $c["name"] }}
                                    </option>
                                @endisset
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Error for caregivers --}}
                @error("caregivers.0") <div>{{ $message }}</div> @enderror
                @error("caregivers.1") <div>{{ $message }}</div> @enderror
                @error("caregivers.2") <div>{{ $message }}</div> @enderror
                @error("caregivers.3") <div>{{ $message }}</div> @enderror

                {{-- Buttons --}}
                <div class="form-group">
                    <button type="submit">Create</button>
                </div>
            </form>
        </div>
        @include('navbar')

    </body>
</html>
