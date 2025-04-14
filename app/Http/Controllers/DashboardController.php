<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\QuizAttemptDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
       $user = Auth()->user();
       $role = $user->role;
       $teacher = Auth::user();
       $schoolId = $teacher->school_id;
       $studentIds = DB::table('users')
                        ->where('role', 'student')
                        ->where('school_id', $schoolId)
                        ->pluck('id');
    //    dd($role);
        // $studentId = 3; // 
        // average score per stack
        $avgByStack = DB::table('quiz_attempts')
                            ->join('stacks', 'quiz_attempts.stack_id', '=', 'stacks.id')
                            ->select('stacks.subject', 'stacks.topic', DB::raw('ROUND(AVG(correct_answers / total_questions) * 100, 1) as average_score'))
                            ->whereIn('quiz_attempts.user_id', $studentIds)
                            ->groupBy('stacks.subject', 'stacks.topic')
                            ->get();

        // Attempt history last 5 overall
        $attempts = QuizAttempt::whereIn('user_id', $studentIds)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        // Most commonly missed questions
        $commonMistakes = DB::table('quiz_attempts_details')
                            ->join('questions', 'quiz_attempts_details.question_id', '=', 'questions.id')
                            ->join('quiz_attempts', 'quiz_attempts.id', '=', 'quiz_attempts_details.quiz_attempt_id')
                            ->whereIn('quiz_attempts.user_id', $studentIds)
                            ->where('quiz_attempts_details.is_correct', 0)
                            ->select('questions.text', DB::raw('COUNT(*) as times_wrong'))
                            ->groupBy('questions.text')
                            ->orderByDesc('times_wrong')
                            ->limit(5)
                            ->get();

        // Answer breakdown for recent attempt
        $latestAttempt = QuizAttempt::whereIn('user_id', $studentIds)
            ->orderBy('created_at', 'desc')
            ->first();

        $answerBreakdown = [];

        if ($latestAttempt) {
            $answerBreakdown = QuizAttemptDetail::with('question')
                ->where('quiz_attempt_id', $latestAttempt->id)
                ->get();
        }

        return view('dashboard', compact(
            'avgByStack', 'attempts', 'commonMistakes', 'answerBreakdown', 'latestAttempt', 'role'
        ));
    }


}

