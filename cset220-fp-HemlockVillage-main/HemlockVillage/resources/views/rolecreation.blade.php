<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Role Creation</title>

        <link rel="stylesheet" href="{{ asset('./css/rolecreation.css') }}">
    </head>
        <body>
            <div class="container">
                <h1>Roles</h1>

                <div class="flexbox">
                    <label>Role Name</label>
                    <input type="text" id="role-name" placeholder="Enter a Role Name" required>
                </div>
                
                <div class="flexbox">
                    <label>Access Level</label>
                    <input type="number" id="access-level" placeholder="Enter Access Level" required>
                </div>
                
                <div class="flexbox">
                    <button id="create-role-btn">Create Role</button>
                    <button type="button" onclick="cancelAction()">Cancel</button>
                </div>

                <div id="success-message" style="display: none;">
                    Role created successfully!
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Access Level</th>
                            </tr>
                        </thead>
                        <tbody id="roles-table-body">
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                // Cancel action redirects to the home page
                function cancelAction() {
                    window.location.href = '/users'; 
                }

                // Fetch all roles and display them
                function fetchRoles() {
                    fetch('/roles')  
                        .then(response => response.json())
                        .then(data => {
                            const tableBody = document.getElementById('roles-table-body');
                            tableBody.innerHTML = '';  

                            data.forEach(role => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${role.role}</td>
                                    <td>${role.access_level}</td>
                                `;
                                tableBody.appendChild(row);
                            });
                        })
                        .catch(error => console.error('Error fetching roles:', error));
                }

                document.getElementById('create-role-btn').addEventListener('click', () => {
                    const roleName = document.getElementById('role-name').value;
                    const accessLevel = document.getElementById('access-level').value;

                    if (!roleName || !accessLevel) {
                        alert("Please fill in all fields.");
                        return;
                    }

                    // Send data to the server to create the role
                    fetch('/create-role', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            role_name: roleName,
                            access_level: accessLevel,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('success-message').style.display = 'block';
                        
                        fetchRoles();
                    })
                    .catch(error => console.error('Error creating role:', error));
                });

                fetchRoles();
            </script>
            @include('navbar')
        </body>
</html>
