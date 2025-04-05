<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $schools = School::all(); 
        return view('profile.edit', [
            'user' => $request->user(),
            'schools' => $schools,  
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'grade_level' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $user->school_id = $request->school_id;

        if ($user->hasRole('student')) {
            $user->student->grade_level = $request->grade_level;
        } elseif ($user->hasRole('teacher')) {
            $user->teacher->department = $request->department;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateSchool(Request $request): RedirectResponse
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',  
        ]);

        $user = $request->user();
        $user->school_id = $request->school_id;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'school-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
