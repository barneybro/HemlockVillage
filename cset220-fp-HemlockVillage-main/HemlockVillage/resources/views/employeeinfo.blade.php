
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Info</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f4ec;
            margin: 20px;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h1, h2 {
            color: #ae370f;
            margin-bottom: 15px;
        }

        .employee-info {
            margin-bottom: 20px;
        }

        .employee-info p {
            margin: 5px 0;
            font-size: 16px;
            color: #481502;
        }

        .employee-info strong {
            color: #ae370f;
        }

        .update-section {
            margin-top: 20px;
        }

        input[type="number"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #c0bba6;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px 15px;
            background-color: #ae370f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #481502;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        </style>
    </head>
        <body>
            <div class="container">
                <h1>Employee Info</h1>
                <div class="employee-info">
                    <p><strong>ID:</strong> {{ $employee->id }}</p>
                    <p><strong>Name:</strong> {{ $employee->user->first_name }} {{ $employee->user->last_name }}</p>
                    <p><strong>Email:</strong> {{ $employee->user->email }}</p>
                    <p><strong>Role:</strong> {{ $employee->user->role->role }}</p>
                    <p><strong>Date of Birth:</strong> {{ $employee->user->date_of_birth }}</p>
                    <p><strong>Phone:</strong> {{ $employee->user->phone_number }}</p>
                    {{-- <p><strong>Joined on:</strong> {{ \Carbon\Carbon::parse($employee->user->updated_at)->format("M d, Y") }}</p> Based on when they were "approved" --}}
                    <p><strong>Salary:</strong> $<span id="current-salary">{{ $employee->salary }}</span></p>
                </div>

                @php
                    $accessLevel = Auth::user()->role->access_level ?? null;
                @endphp


                {{-- Display updating salary if admin --}}
                @if($accessLevel === 1)
                    <div class="update-section">
                        <h2>Update Salary</h2>
                        <input type="number" id="new-salary" placeholder="Enter new salary">
                        <button onclick="updateSalary({{ $employee->id }})">Update Salary</button>
                        <div id="message" class="message"></div>
                    </div>
                @endif
            </div>

            <script>
                function updateSalary(employeeId) {
                    const newSalary = document.getElementById('new-salary').value;
                    const message = document.getElementById('message');

                    if (!newSalary) {
                        message.innerHTML = 'Please enter a valid salary.';
                        message.className = 'message error';
                        message.style.display = 'block';
                        return;
                    }

                    fetch(`/employees/${employeeId}/update-salary`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ new_salary: newSalary })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            message.innerHTML = data.error;
                            message.className = 'message error';
                            message.style.display = 'block';
                        } else {
                            document.getElementById('current-salary').textContent = data.new_salary;
                            message.innerHTML = data.message;
                            message.className = 'message success';
                            message.style.display = 'block';
                        }
                    })
                    .catch(() => {
                        message.innerHTML = 'An error occurred. Please try again.';
                        message.className = 'message error';
                        message.style.display = 'block';
                    });
                }
            </script>
        @include('navbar')

        </body>
</html>
