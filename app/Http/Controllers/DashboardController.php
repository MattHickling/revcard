<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $openStacks = $user->stacks()->where('open', true)->get(); 

        return view('dashboard', compact('openStacks')); 
    }
}
