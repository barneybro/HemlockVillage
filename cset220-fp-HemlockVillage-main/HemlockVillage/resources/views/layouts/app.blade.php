<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Include the navbar -->
    @include('navbar')

    <div class="container">
        @yield('content') {{-- Main content will be injected here --}}
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
