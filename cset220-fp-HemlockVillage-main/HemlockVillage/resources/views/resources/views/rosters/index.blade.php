

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Roster</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #333333;
        }

        .date-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .date-form input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            background-color: #fafafa;
            color: #555555;
            width: 200px;
            box-sizing: border-box;
        }

        .date-form button {
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .date-form button:hover {
            background-color: #0056b3;
        }

        .roster {
            margin-bottom: 30px;
        }

        .roster h2 {
            background-color: gray;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .roster table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: gray;
            color: white;
        }

        .btn-primary {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: grey;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Roster</h1>

        <div class="date-form">
            <form method="GET" action="{{ route('rosters.view') }}">
                <label for="roster-date">Select Date:</label>
                <input type="date" id="roster-date" name="date" required>
                <button type="submit">View Roster</button>
            </form>
        </div>

        @if($roster->isEmpty())
            <p>No roster found for the selected date.</p>
        @else
            <div class="roster">
                <h2>Roster for {{ $date }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roster as $entry)
                            <tr>
                                <td>{{ $entry->role }}</td>
                                <td>{{ $entry->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <a href="/create-roster" class="btn-primary">Create Roster</a>
    </div>
</body>
</html>
