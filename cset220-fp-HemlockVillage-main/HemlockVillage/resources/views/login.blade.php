<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">

		<title>Login</title>
		<link rel="stylesheet" href="{{ asset('./css/login.css') }}">

	</head>

	<body>
		<div class="section">
			{{-- Error section --}}
			@if ($errors->any())
				<div class="error">
					@foreach ($errors->all() as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif

			{{-- Logout redirecting message --}}
			@if (session("success"))
				<div class="success">
					{{ session("success") }}
				</div>
			@endif

			{{-- Login form --}}
			<form action="/login" method="post" class="form">
				@csrf
				<h1>Log In</h1>
				<div class="input">
					<label for="email">Email</label>
					<input type="email" name="email" id="email" required maxlength="100">
				</div>

				<div class="input">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" required>
				</div>

				<div>
					<button type="submit">Login</button>
					<a href="/signup">Sign up</a>
				</div>
			</form>
		</div>
	</body>
</html>
