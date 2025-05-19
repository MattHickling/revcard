<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'school_id' => 'required|exists:schools,id',
            'role' => 'required|in:teacher,student',
        ]);

        $token = Str::random(40);

        Invite::create([
            'email' => $request->email,
            'school_id' => $request->school_id,
            'role' => $request->role,
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($request->email)->send(new InviteUserMail($token));

        return back()->with('success', 'Invitation sent!');
    }

    public function create()
    {
        $schools = School::all();
        return view('emails.invite', compact('schools'));
    }

}
