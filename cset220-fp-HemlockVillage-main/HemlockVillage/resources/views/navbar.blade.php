<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nav Bar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            right: 0;
            background-color: #333;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        nav ul li {
            margin: 20px 0;
        }

        nav ul li a {
            text-decoration: none;
            font-size: 18px;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
        }

        .hamburger {
            font-size: 30px;
            color: black;
            background: none;
            border: none;
            padding: 10px;
            cursor: pointer;
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1;
            text-align: right;
            width: 50px;
        }

        .hamburger:hover {
            background: none;
            color: black;
        }

        .hamburger:focus {
            outline: none;
            background-color: #f7f4ec;
        }

        nav.open {
            width: 250px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            visibility: hidden;
            transition: visibility 0.3s ease;
        }

        .overlay.show {
            visibility: visible;
        }

        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    @auth
        <button class="hamburger" onclick="toggleMenu()">☰</button>

        <nav id="sidebar">
            <ul>
                @php
                    $accessLevel = Auth::user()->role->access_level;
                @endphp

                @if ($accessLevel === 1) {{-- Admin --}}
                    <li><a href="/users">Users</a></li>
                    <li><a href="/search/patients">Patients</a></li>
                    <li><a href="{{ route('employeesearch') }}">Employees</a></li>
                    <li><a href="{{ route('registrationapproval.index') }}">Registration Approval</a></li>
                    <li><a href="{{ route('rolecreation.index') }}">Roles</a></li>
                    <li><a href="/report">Report</a></li>
                    <li><a href="/payment">Bills</a></li>
                    <li><a href="/roster/create">Create Roster</a></li>
                    <li><a href="/schedule">Schedule Appointment</a></li>

                @elseif ($accessLevel === 2) {{-- Supervisor --}}
                    <li><a href="/users">Users</a></li>
                    <li><a href="/search/patients">Patients</a></li>
                    <li><a href="{{ route('employeesearch') }}">Employees</a></li>
                    <li><a href="{{ route('registrationapproval.index') }}">Registration Approval</a></li>
                    <li><a href="/report">Report</a></li>
                    <li><a href="/roster/create">Create Roster</a></li>
                    <li><a href="/schedule">Schedule Appointment</a></li>

                @elseif ($accessLevel === 3) {{-- Doctor --}}
                    <li><a href="/home">Home</a></li>
                    <li><a href="/search/patients">Patients</a></li>

                @elseif ($accessLevel === 4) {{-- Caregiver --}}
                    <li><a href="/home">Home</a></li>
                    <li><a href="/search/patients">Patients</a></li>

                @elseif ($accessLevel === 5) {{-- Patient --}}
                    <li><a href="/home">Home</a></li>

                @elseif ($accessLevel === 6) {{-- Family Member --}}
                    <li><a href="/home">Home</a></li>

                @endif

                <li><a href="{{ route('roster.show') }}">Roster</a></li>

                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>
    @endauth

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>
