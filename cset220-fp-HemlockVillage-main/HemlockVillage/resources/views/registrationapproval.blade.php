<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Approval</title>

    <link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">
    <style>
        .container{
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration Approval</h1>

        @if(session('success'))
            <div style="color: green; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="color: red; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        @if ($users->isEmpty())
            <p>No pending registrations found.</p>
        @else
            @foreach ($users as $user)
                <div class="user-card">
                    <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                    <p>Email: {{ $user->email }}</p>
                    <p>Date of Birth: {{ $user->date_of_birth }}</p>
                    <p>Role: {{ $user->role }}</p>
                    <p>Phone: {{ $user->phone_number }}</p>

                    <div class="action-buttons">
                        <form action="{{ route('users.approve', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-approve">Approve</button>
                        </form>

                        <form action="{{ route('users.reject', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-reject">Reject</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @include('navbar')
</body>
</html>
