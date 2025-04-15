<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\TeacherComment;
use App\Models\QuizAttemptDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
{
    $teacher = Auth::user();
    $role = $teacher->role;

    $students = User::where('role', 'student')
        ->where('school_id', $teacher->school_id)
        ->with([
            'quizAttempts.details.question',
            'latestQuizAttempt.details.question',
            'teacherComment'
        ])
        ->get();
// dd($students);

    // Average score by stack 
    $avgByStack = DB::table('quiz_attempts')
        ->join('stacks', 'quiz_attempts.stack_id', '=', 'stacks.id')
        ->select('stacks.subject', 'stacks.topic', DB::raw('ROUND(AVG(correct_answers / total_questions) * 100, 1) as average_score'))
        ->whereIn('quiz_attempts.user_id', $students->pluck('id'))
        ->groupBy('stacks.subject', 'stacks.topic')
        ->get();

    // Most missed questions
    $commonMistakes = DB::table('quiz_attempts_details')
        ->join('questions', 'quiz_attempts_details.question_id', '=', 'questions.id')
        ->join('quiz_attempts', 'quiz_attempts.id', '=', 'quiz_attempts_details.quiz_attempt_id')
        ->whereIn('quiz_attempts.user_id', $students->pluck('id'))
        ->where('quiz_attempts_details.is_correct', 0)
        ->select('questions.text', DB::raw('COUNT(*) as times_wrong'))
        ->groupBy('questions.text')
        ->orderByDesc('times_wrong')
        ->limit(5)
        ->get();

    return view('dashboard', compact('students', 'avgByStack', 'commonMistakes', 'role'));
}


}

