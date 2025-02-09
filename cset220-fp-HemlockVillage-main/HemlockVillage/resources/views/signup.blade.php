<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">

		<title>Sign Up</title>
		<link rel="stylesheet" href="{{ asset('./css/signup.css') }}">
		<link rel="stylesheet" href="{{ asset('./css/style.css') }}">
	</head>

	<body>
		<div class="section">
			{{-- Error Section --}}
			@if ($errors->any())
				<div class="">
					@foreach ($errors->all() as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif

			{{-- Sign up Form --}}
			<form action="/signup" method="post" class="form">
				@csrf
				<h1>Sign Up</h1>
					<label for="role_selection">Role</label>
					<select name="role" id="role_selection">
						@foreach ($roles as $role)
							<option value="{{ $role->id }}">{{ $role->role }}</option>
						@endforeach
					</select>

				<div class="flex_column">
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" required maxlength="50">

					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" required maxlength="50">

					<label for="email">Email Address</label>
					<input type="email" name="email" id="email" required maxlength="100">

					<label for="date_of_birth">Date of Birth</label>
					<input type="date" name="date_of_birth" id="date_of_birth" required>

					<label for="phone_number">Phone Number</label>
					<input type="tel" name="phone_number" id="phone_number"  required maxlength="20">

					<label for="password">Password</label>
					<input type="password" name="password" id="password"  required>

					<label for="password_confirmation">Confirm Password</label>
					<input type="password" name="password_confirmation" id="password_confirmation" required>
				</div>

				<div id="emergencyInfo" class="hidden_none flex_column">
					<label for="family_code">Family Code</label>
					<input type="text" name="family_code" id="family_code"  value="{{ $familyCode }}" readonly>

					<label for="econtact_name">Emergency Contact Name</label>
					<input type="text" name="econtact_name" id="econtact_name" maxlength="128">

					<label for="econtact_phone">Emergency Contact Phone</label>
					<input type="tel" name="econtact_phone" id="econtact_phone" maxlength="20">

					<label for="econtact_relation">Relation to Patient</label>
					<input type="text" name="econtact_relation" id="econtact_relation" maxlength="50">
				</div>

				<div class="flex_column">
					<button type="submit">Sign Up!</button>
					<button type="reset">Cancel</button>
				</div>
			</form>
		</div>
		<script>
			window.onload = () =>
			{
				const roleSelection = document.querySelector("#role_selection")
				if (!roleSelection) return

				const eDiv = document.querySelector("#emergencyInfo")
				if (!eDiv) return

				roleSelection.onchange = () =>
				{
					if (roleSelection.value == 5)
						eDiv.classList.remove("hidden_none")

					else
						eDiv.classList.add("hidden_none")
				}
			}
		</script>
	</body>
</html>
