<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherComment;

class TeacherCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'comment' => 'nullable|string',
        ]);

        TeacherComment::updateOrCreate(
            ['student_id' => $request->student_id],
            ['comment' => $request->comment]
        );

        return redirect()->back()->with('success', 'Comment saved!');
    }
}
