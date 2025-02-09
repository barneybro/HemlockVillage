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
				background-color: #f7f4ec;
				margin: 20px;
				color: #3b1d0b;
			}

			h1 {
				text-align: center;
				color: #ae370f;
				margin-bottom: 30px;
				font-size: 28px;
			}

			.table {
				width: 100%;
				max-width: 600px;
				margin: 0 auto;
				border-collapse: collapse;
				background-color: #fff;
				border-radius: 12px;
				overflow: hidden;
				box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
			}

			.table tr {
				border-bottom: 1px solid #ddd;
				transition: background-color 0.3s ease;
			}

			.table tr:last-child {
				border-bottom: none;
			}

			.table th {
				width: 35%;
				background-color: #ae370f;
				color: white;
				padding: 15px;
				text-align: left;
				font-weight: bold;
				border-right: 2px solid #f7f4ec;
			}

			.table td {
				padding: 15px;
				background-color: #fdf7f3;
				color: #481502;
				text-align: left;
			}

			.table tr:nth-child(even) td {
				background-color: #f7e3d4;
			}

			.table tr:hover td {
				background-color: #f4d0bc;
			}

			.table tr:first-child th {
				border-top-left-radius: 12px;
			}

			.table tr:first-child td {
				border-top-right-radius: 12px;
			}

			.table tr:last-child th {
				border-bottom-left-radius: 12px;
			}

			.table tr:last-child td {
				border-bottom-right-radius: 12px;
			}
		</style>
	</head>

	<body>

		{{-- No roster message --}}
		@if(isset($message))
			<div>
				<p>{{ $message }}</p>
			</div>

		{{-- Roster exists --}}
		@else
			<h1>Roster for Date: {{ $data["date"] }}</h1>

			<!-- Display roster data in a table -->
			<table class="table">
				<tbody>
					<tr>
						<th>Supervisor</th>
						<td>{{ $data["supervisor_name"] }}</td>
					</tr>
					<tr>
						<th>Doctor</th>
						<td>{{ $data["doctor_name"] }}</td>
					</tr>
					<tr>
						<th>Caregiver One</th>
						<td>{{ $data["caregivers"]["caregiver_one_name"] }}</td>
					</tr>
					<tr>
						<th>Caregiver Two</th>
						<td>{{ $data["caregivers"]["caregiver_two_name"] }}</td>
					</tr>
					<tr>
						<th>Caregiver Three</th>
						<td>{{ $data["caregivers"]["caregiver_three_name"] }}</td>
					</tr>
					<tr>
						<th>Caregiver Four</th>
						<td>{{ $data["caregivers"]["caregiver_four_name"] }}</td>
					</tr>
				</tbody>
			</table>
			
		@endif

		@include("navbar");
	</body>
</html>
