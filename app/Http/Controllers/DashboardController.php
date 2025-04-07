<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $openStacks = $user->stacks()->where('open', true)->get(); 
        $pupilquizes = QuizAttempt::where('user_id', $user->id)
                                    ->leftJoin('users', 'quiz_attempts.user_id', '=', 'users.id')
                                    ->get();
        // dd($pupilquizes);
        // if($user->role == "teacher"){
        //     return DataTables::of($openStacks )->make(true);
        // }else{
            return view('dashboard', compact('openStacks')); 
        // }
        
    }
}
