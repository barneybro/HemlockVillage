<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">

		<title>Users</title>
		<link rel="stylesheet" href="{{ asset("./css/users.css") }}">
	</head>

	<body>
		<div class="container">
			<h2 class="page_title">Users</h2>

			{{-- Table --}}
			<table class="info_table center_horizontal">
				<thead class="sticky">
					<tr>
						<th>User ID</th>
						<th>Full Name</th>
						<th>Email Address</th>
						<th>Phone Number</th>
						<th>Date of Birth</th>
						<th>Role</th>
					</tr>
				</thead>

				<tbody>
					@foreach($data as $d)
						<tr>
							<td>{{ $d->id }}</td>
							<td>{{ $d->first_name }} {{ $d->last_name }} </td>
							<td>{{ $d->email }}</td>
							<td>{{ $d->phone_number }}</td>
							<td>{{ $d->date_of_birth }}</td>
							<td>{{ $d->role }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			{{-- Pagination --}}
			@isset($pagination)
				<div class="pagination">
					{{-- Button previous --}}
					@if($pagination['prev_page_url'])
						<a href="{{ $pagination['prev_page_url'] }}">Previous</a>
					@endif

					{{-- Page Numbers --}}
					@foreach($pagination['pages'] as $page)
						@if($page == $pagination['current_page'])
							<span class="current-page">{{ $page }}</span>
						@else
							<a href="{{ $data->url($page) }}">{{ $page }}</a>
						@endif
					@endforeach

					{{-- Button next --}}
					@if($pagination['next_page_url'])
						<a href="{{ $pagination['next_page_url'] }}">Next</a>
					@endif
				</div>
			@endisset

		</div>

		@include('navbar')

	</body>
</html>
