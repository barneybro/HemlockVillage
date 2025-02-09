<?php

namespace App\Http\Controllers\Regular;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Http\Controllers\Controller; // This line is important

class RosterController extends Controller
{
    /**
     * Show the main roster page (with date selector).
     */
    public function index()
    {
        // Return the roster index page where the user can select a date
        return view('rosters');
    }

    /**
     * Show the roster for a specific date.
     */
    public function viewRoster(Request $request)
    {
        return 'slr';
        // Validate that a valid date is provided
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        // Get the date from the request
        $date = $validated['date'];

        // Retrieve the roster for the specific date
        $roster = Roster::whereDate('date', $date)->get();

        // If no roster is found for the selected date, show an error message
        if ($roster->isEmpty()) {
            return redirect()->route('rosters.index')->with('error', 'No roster found for the selected date.');
        }

        // Return the view with the roster data and the selected date
        return view('rosters.view', compact('roster', 'date'));
    }
}
