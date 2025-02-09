<?php
namespace App\Http\Controllers\Regular;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public static function showLoginForm()
    {
        return view("login");
    }

    public static function login(Request $Request)
    {
        $credentials = $Request->validate([
            "email" => [ "bail", "required" ],
            "password" => [ "bail", "required" ]
        ]);

        // Fail to match credentials
        if (!Auth::attempt($credentials))
            return redirect()->back()->withErrors([ "Invalid email address and password combination." ]);

        // Not approved
        if (!Auth::user()->approved)
            return redirect()->back()->withErrors([ "Please wait for your account to be approved." ]);

        // Success
        $Request->session()->regenerate();

        // Go to home page
        return redirect()->intended("/home");
    }

    public static function logout(Request $request)
    {
        $loggedIn = Auth::check();

        // Logout the authenticated user
        Auth::logout();

        // Invalidate the session to clear all session data
        $request->session()->invalidate();

        // Regenerate the session ID for security
        $request->session()->regenerateToken();

        // Only display message if the user was logged in and successfully logged out
        if ($loggedIn) session()->flash('success', 'Logged out successfully!');

        return redirect()->route("login.form");
    }
}
