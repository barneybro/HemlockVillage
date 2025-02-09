<html>
    <head>
        <title>Edit Role</title>
		<link rel="stylesheet" href="{{ asset('./css/editroles.css') }}">
    </head>

    <body>
        <div class="container">
            <h1>Edit Role</h1>

            <form action="/update-role" method="POST">


                <div class="flexbox">
                    <label for="employee-name">Employee Name:</label>
                    <input type="text" id="employee-name" name="employee_name" value="Jimmy Butler" readonly>
                </div>

                <div class="flexbox">
                    <label for="role-name">Role Name:</label>
                    <input type="text" id="role-name" name="role_name" value="Admin" required>
                </div>

                <div class="flexbox">
                    <label for="access-level">Access Level:</label>
                    <input type="text" id="access-level" name="access_level" value="5" required>
                </div>

                <div class="flexbox">
                    <button type="submit">Save Changes</button>
                    <button type="button" class="btn-danger">Delete Role</button>
                    <button type="button">Cancel</button>
                </div>
            </form>
        </div>
        @include('navbar')

    </body>
</html>
