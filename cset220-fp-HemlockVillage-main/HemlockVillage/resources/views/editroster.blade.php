<html>
    <head>
        <title>Edit Roster</title>
		<link rel="stylesheet" href="{{ asset('./css/editroster.css') }}">

    </head>

    <body>
        <div class="container">
            <h1>Edit Roster</h1>
            <form action="/update-roster" method="POST">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="2024-11-25" readonly>
                </div>

                <div class="form-group">
                    <label for="supervisor">Supervisor:</label>
                    <select id="supervisor" name="supervisor" required>
                        <option value="Nicholas Helcok" selected>Nicholas Helcok</option>
                        <option value="Gage Cooper">Gage Cooper</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="doctor">Doctor:</label>
                    <select id="doctor" name="doctor" required>
                        <option value="Dr. Cooper" selected>Dr. Cooper</option>
                        <option value="Dr. Helock">Dr. Helock</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="caregivers">Caregivers:</label>
                    <select id="caregivers" name="caregivers[]" multiple required>
                        <option value="Jane Doe" selected>Jane Doe</option>
                        <option value="Emily White" selected>Emily White</option>
                        <option value="Michael Green">Michael Green</option>
                        <option value="Sarah Johnson">Sarah Johnson</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">Save Changes</button>
                    <button type="button" class="btn-danger">Delete Roster</button>
                    <button type="button" class="btn-secondary">Cancel</button>
                </div>
            </form>
        </div>

        @include('navbar')

    </body>
</html>
