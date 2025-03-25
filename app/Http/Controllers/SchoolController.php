<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $schools = School::where('EstablishmentName', 'like', '%' . $query . '%')->get();
    
        return response()->json($schools);
    }
    
}
