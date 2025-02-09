<html>
    <head>
        <title>List of Patients / Search Patient</title>
        <link rel="stylesheet" href="{{ asset('./css/searchpatient.css') }}">
        <style>
            .container{
                margin-top: 50px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>Search for a Patient</h1>

        <div class="search-bar">
            <div>
                <label for="patient-id">Search by Patient ID</label>
                <input type="text" id="patient-id" placeholder="Enter Patient ID">
            </div>
            <div>
                <label for="user-id">Search by User ID</label>
                <input type="text" id="user-id" placeholder="Enter User ID">
            </div>
            <div>
                <label for="name">Search by Name</label>
                <input type="text" id="name" placeholder="Enter Name">
            </div>
            <div>
                <label for="dob">Search by DOB</label>
                <input type="text" id="dob" placeholder="Enter DOB (YYYY-MM-DD)">
            </div>
            <div>
                <label for="emergency-contact">Search by Emergency Contact</label>
                <input type="text" id="emergency-contact" placeholder="Enter Emergency Contact">
            </div>
            <div>
                <label for="emergency-contact-name">Search by Emergency Contact Name</label>
                <input type="text" id="emergency-contact-name" placeholder="Enter Emergency Contact Name">
            </div>

                <button onclick="searchPatients()">Search</button>
            </div>
        <div id="patient-list"></div>

    <script>
        function searchPatients() {
            const patientId = document.getElementById('patient-id').value;
            const userId = document.getElementById('user-id').value;
            const name = document.getElementById('name').value;
            const dob = document.getElementById('dob').value;
            const emergencyContact = document.getElementById('emergency-contact').value;
            const emergencyContactName = document.getElementById('emergency-contact-name').value;

            const params = new URLSearchParams({
                patient_id: patientId,
                user_id: userId,
                name: name,
                dob: dob,
                emergency_contact: emergencyContact,
                emergency_contact_name: emergencyContactName
            });

                fetch(`/patients/search?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        const patientList = document.getElementById('patient-list');
                        patientList.innerHTML = '';  // Clear previous content

                        if (data.length === 0) {
                            patientList.innerHTML = '<p>No patients found matching your criteria.</p>';
                            return;
                        }

                    data.forEach(patient => {
                        const card = document.createElement('div');
                        card.classList.add('patient-card');
                        card.innerHTML = `
                            <h3>Patient Name: ${patient.name}</h3>
                            <p>Patient ID: ${patient.patient_id}</p>
                            <p>User ID: ${patient.user_id}</p>
                            <p>DOB: ${patient.date_of_birth}</p>
                            <p>Emergency Contact: ${patient.emergency_contact}</p>
                            <p>Emergency Contact Name: ${patient.emergency_contact_name}</p>
                            <button onclick="window.location.href='/patients/${patient.patient_id}'">View Patient Info</button>
                        `;
                        patientList.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    @include('navbar')
</body>
</html>
