<html>
    <head>
        <title>Roster</title>
		<link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">

        <style>
            a{
                color: #481502; 
            }
            th, td{
                width: 50%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Roster</h1>

            <div class="roster">
                <h2>Roster for 2024-11-25</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Doctor</td>
                            <td>Dr. Cooper</td>
                        </tr>
                        <tr>
                            <td>Supervisor</td>
                            <td>John Smith</td>
                        </tr>
                        <tr>
                            <td>Caregiver</td>
                            <td>Jane Doe</td>
                        </tr>
                        <tr>
                            <td>Caregiver</td>
                            <td>Emily White</td>
                        </tr>
                    </tbody>
                </table>
                <a href="/edit-roster/2024-11-25" class="btn-primary">Edit Roster</a>

            </div>

            <div class="roster">
                <h2>Roster for 2024-11-26</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Doctor</td>
                            <td>Dr. Helock</td>
                        </tr>
                        <tr>
                            <td>Supervisor</td>
                            <td>Alice Brown</td>
                        </tr>
                        <tr>
                            <td>Caregiver</td>
                            <td>Michael Green</td>
                        </tr>
                        <tr>
                            <td>Caregiver</td>
                            <td>Sarah Johnson</td>
                        </tr>
                    </tbody>
                </table>
                <a href="/edit-roster/2024-11-25" class="btn-primary">Edit Roster</a>

            </div>

            <a href="/create-roster" class="btn-primary">Create Roster</a>

        </div>
        @include('navbar')

    </body>
</html>
