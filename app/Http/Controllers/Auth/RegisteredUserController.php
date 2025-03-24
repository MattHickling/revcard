<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Student; 
use App\Models\Teacher; 
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Permission;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $schools = \App\Models\School::all();
        return view('auth.register', compact('schools'));    
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher,admin'], 
            'school_id' => 'required|exists:schools,id', 
            'grade_level' => 'nullable|string|max:255',  
            'department' => 'nullable|string|max:255',
        ]);
        
        $role = $validated['role']; 

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'school_id' => $validated['school_id'],  
        ]);

        if ($validated['role'] == 'student') {
            Student::create([
                'user_id' => $user->id,
                'school_name_student' => $validated['school_name'],
                'grade_level' => $validated['grade_level'],
            ]);
        } elseif ($validated['role'] == 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'school_name_teacher' => $validated['school_name'],
                'department' => $validated['department'],
            ]);
        }

        $user->assignRole($role);

        event(new Registered($user));
        Auth::login($user);


        return redirect(route('dashboard', absolute: false));
    }

    public function associateSchool(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'grade_level' => 'nullable|string|max:255', 
            'department' => 'nullable|string|max:255', 
        ]);

        $user = auth()->user();

        if ($user->hasRole('student')) {
            $user->student()->update([
                'school_name_student' => $validated['school_name'],
                'grade_level' => $validated['grade_level'],
            ]);
        } elseif ($user->hasRole('teacher')) {
            $user->teacher()->update([
                'school_name_teacher' => $validated['school_name'],
                'department' => $validated['department'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'School associated successfully');
    }

    
}
