<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
use App\Models\School;
use Illuminate\Support\Str;
use App\Mail\InviteUserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InviteController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'school_id' => 'required|exists:schools,id',
            'role' => 'required|in:teacher,student,admin',
        ]);
    
        $token = Str::random(40);
    
        $user = User::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'school_id' => $request->school_id,
            'role' => $request->role,
            'status' => 'pending',  
            'password' => '',       
        ]);
    
        $invite = Invite::create([
            'user_id' => $user->id,
            'token' => $token,
            'email' => $request->email, 
            'school_id' => $request->school_id,
            'role' => $request->role, 
            'expires_at' => now()->addDays(7),
        ]);
        
        Mail::to($request->email)->send(new InviteUserMail($invite));

        return back()->with('success', 'Invitation sent!');
    }
    

    public function create()
    {
        $teacher = Auth::user();
        $role = $teacher->role;
      
        return view('auth.register-invite', compact('role'));
    }

   

}
