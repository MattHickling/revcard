<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\QuizAttemptDetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
       
        // $studentId = 3; // 
        // Fetch average score per stack (for all students)
        $avgByStack = DB::table('quiz_attempts')
            ->join('stacks', 'quiz_attempts.stack_id', '=', 'stacks.id')
            ->select('stacks.subject', 'stacks.topic', DB::raw('ROUND(AVG(correct_answers / total_questions) * 100, 1) as average_score'))
            // ->where('quiz_attempts.user_id', $studentId)
            ->groupBy('stacks.subject', 'stacks.topic')
            ->get();

        // Attempt history last 5 overall
        $attempts = QuizAttempt::orderBy('created_at', 'desc')
            // ->where('user_id', $studentId)
            ->take(5)
            ->get();

        // Most commonly missed questions
        $commonMistakes = DB::table('quiz_attempts_details')
            ->join('questions', 'quiz_attempts_details.question_id', '=', 'questions.id')
            ->select('questions.text', DB::raw('COUNT(*) as times_wrong'))
            ->where('quiz_attempts_details.is_correct', 0)
            // ->whereIn('quiz_attempt_id', function($query) use ($studentId) {
            //     $query->select('id')
            //           ->from('quiz_attempts')
            //           ->where('user_id', $studentId);
            // })
            ->groupBy('questions.text')
            ->orderByDesc('times_wrong')
            ->limit(5)
            ->get();

        // Answer breakdown for recent attempt
        $latestAttempt = QuizAttempt::orderBy('created_at', 'desc')
            // ->where('user_id', $studentId)
            ->first();

        $answerBreakdown = [];

        if ($latestAttempt) {
            $answerBreakdown = QuizAttemptDetail::with('question')
                ->where('quiz_attempt_id', $latestAttempt->id)
                ->get();
        }

        return view('dashboard', compact(
            'avgByStack', 'attempts', 'commonMistakes', 'answerBreakdown', 'latestAttempt'
        ));
    }


    }

