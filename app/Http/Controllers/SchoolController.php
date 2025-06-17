<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $schools = DB::table('schools')
                        ->where('EstablishmentName', 'LIKE', '%' . $query . '%')
                        ->limit(10)
                        ->get();
    
        return response()->json($schools);
    }
    
}
