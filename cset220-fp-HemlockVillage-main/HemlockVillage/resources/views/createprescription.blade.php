<html>
    <head>
        <title>Create Prescription</title>
		<link rel="stylesheet" href="{{ asset('./css/createprescription.css') }}">

    </head>

    <body>
        <div class="container">
            <h1>Create Prescription</h1>

            <form action="/create-prescription" method="POST">

                <div class="form-group">
                    <label>Patient ID:</label>
                    <input type="text" id="patient-id" name="patient_id" required>
                </div>



                <div class="form-group">
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="morning-meds">Morning Meds:</label>
                    <input type="text" id="morning-meds" name="morning_meds" placeholder="Enter morning medications" required>
                </div>

                <div class="form-group">
                    <label for="afternoon-meds">Afternoon Meds:</label>
                    <input type="text" id="afternoon-meds" name="afternoon_meds" placeholder="Enter afternoon medications" required>
                </div>

                <div class="form-group">
                    <label for="night-meds">Night Meds:</label>
                    <input type="text" id="night-meds" name="night_meds" placeholder="Enter night medications" required>
                </div>

                <div class="form-group">
                    <button type="submit">Create</button>
                    <button type="button" class="btn-secondary">Go Back to Patient Home</button>
                </div>
            </form>
        </div>

        @include('navbar')

    </body>
</html>
