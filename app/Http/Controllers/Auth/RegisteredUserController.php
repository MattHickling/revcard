<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Invite;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
   
    public function create(): View
    {
        $schools = School::all();
        return view('auth.register', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher,admin'],
            'school_id' => 'required|exists:schools,id',
            'grade_level' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $school = School::find($validated['school_id']);
        $school_name = $school ? $school->EstablishmentName : null;
        $role = $validated['role'];

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'school_id' => $validated['school_id'],
            'status' => 'active', 
        ]);

        if ($role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'school_name_student' => $school_name,
                'grade_level' => $validated['grade_level'] ?? null,
            ]);
        } elseif ($role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'school_name_teacher' => $school_name,
                'department' => $validated['department'] ?? null,
            ]);
        }

        $user->assignRole($role);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard'));
    }

    public function showAcceptInviteForm(string $token): View
    {
        $invite = Invite::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = $invite->user;

        return view('auth.accept-invite', compact('user', 'token'));
    }

    public function acceptInvite(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required|exists:invites,token',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $invite = Invite::where('token', $request->token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = $invite->user;

        $user->password = Hash::make($request->password);
        $user->status = 'active'; 
        $user->save();

        $invite->delete();

        Auth::login($user);

        return redirect(route('dashboard'))->with('success', 'Your account has been activated!');
    }

    public function associateSchool(Request $request): RedirectResponse
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


    public function showInviteForm(string $token): View
    {
        $invite = Invite::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('emails.invite', [
            'invite' => $invite,
        ]);
    }

    public function showRegistrationForm($token)
    {
        $invite = Invite::where('token', $token)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$invite) {
            return redirect('/')->withErrors('Invalid or expired invitation link.');
        }

        return view('auth.register-complete', compact('invite'));
    }


    public function register(Request $request, $token)
    {
        $invite = Invite::where('token', $token)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$invite) {
            return redirect('/')->withErrors('Invalid or expired invitation link.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $invite->email)->first();

        if (!$user) {
            $user = User::create([
                'email' => $invite->email,
                'password' => bcrypt($request->password),
                'status' => 'active',
                'role' => $invite->role,
                'school_id' => $invite->school_id,
            ]);
        } else {
            $user->password = bcrypt($request->password);
            $user->status = 'active';
            $user->save();
        }
        $invite->delete();

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Welcome! Your account has been activated.');
    }

}
