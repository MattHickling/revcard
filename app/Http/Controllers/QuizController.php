<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\QuizAttemptDetail;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function saveQuizResult(Request $request)
    {
        $request->validate([
            'stack_id' => 'required|integer',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.user_answer' => 'required|string',
            'answers.*.correct_answer' => 'required|string',
        ]);

        $user = auth()->user();
        $stackId = $request->stack_id;
        $answers = $request->answers;

        $correctCount = 0;
        $wrongCount = 0;
        $totalQuestions = count($answers);

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'stack_id' => $stackId,
            'attempt_number' => QuizAttempt::where('user_id', $user->id)->where('stack_id', $stackId)->count() + 1,
            'correct_answers' => 0,
            'wrong_answers' => 0,
            'total_questions' => $totalQuestions,
        ]);

        foreach ($answers as $answer) {
            $isCorrect = $answer['user_answer'] === $answer['correct_answer'];

            QuizAttemptDetail::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $answer['question_id'],
                'user_answer' => $answer['user_answer'],
                'correct_answer' => $answer['correct_answer'],
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) {
                $correctCount++;
            } else {
                $wrongCount++;
            }
        }
// dd(Auth::user());
        if ($totalQuestions == 5) {
            if ($correctCount <= 3) {
                $message = "You got " . $correctCount . " correct, it might be good to retry these questions.";
            } else {
                $message = "Well done! You got ". $correctCount . " questions correct. I think you are ready to try some different questions or move onto a different subject.";
            }
        }
        
        if ($totalQuestions == 10) {
            if ($correctCount <= 7) {
                $message = "You got " . $correctCount . " correct, it might be good to retry these questions.";
            } else {
                $message = "Well done " . Auth::user()->first_name . "! I think you are ready to try some different questions or move onto a different subject.";
            }
        }
        
        if ($totalQuestions == 20) {
            if ($correctCount <= 15) {
                $message = Auth::user()->first_name . ", you got " . $correctCount . " question correct, it might be good to retry these questions.";
            } else {
                $message = "Well done " . Auth::user()->first_name . "! I think you are ready to try some different questions or move onto a different subject.";
            }
        }
        

        $attempt->update([
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
        ]);

        return response()->json([
            'message' => $message,
            'attempt_id' => $attempt->id,
            'correct' => $correctCount,
            'wrong' => $wrongCount,
        ]);
    }

    public function showQuizSummary($stackId)
    {
    
        $attempt = QuizAttempt::with('details.question')
                                ->where('stack_id', $stackId)
                                ->latest()
                                ->firstOrFail();

        $questions = $attempt->details->pluck('question');
        $correctAnswers = []; 

        foreach ($attempt->details as $detail) {
            $question = $detail->question;

            if ($question) {
                $correctAnswers[$question->id] = match ($question->correct_answer) {
                    'A' => $question->option_1,
                    'B' => $question->option_2,
                    'C' => $question->option_3,
                    'D' => $question->option_4,
                    default => 'Answer not available',
                };
            }
        }

    
        return view('quiz.summary', compact('attempt', 'questions', 'correctAnswers', 'stackId'));
    }

}
