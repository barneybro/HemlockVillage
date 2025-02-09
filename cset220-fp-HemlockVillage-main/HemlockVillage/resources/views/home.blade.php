@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <h1>Welcome to the Application</h1>
    <p>Your access level: {{ Auth::user()->role->access_level ?? 'Unknown' }}</p>
    <p>Welcome to the home page. You should be logged in if you are able to see this.</p>

    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection
