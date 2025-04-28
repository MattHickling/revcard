<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherComment;

class TeacherController extends Controller
{
    public function comment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:1000',
        ]);

        TeacherComment::create([
            'teacher_id' => auth()->id(),
            'student_id' => $request->student_id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comment added.');
    }

}
