<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\QuizAttemptDetail;
use Illuminate\Http\Request;

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

        $attempt->update([
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
        ]);

        return response()->json([
            'message' => 'Quiz results saved successfully!',
            'attempt_id' => $attempt->id,
            'correct' => $correctCount,
            'wrong' => $wrongCount,
        ]);
    }

    public function showQuizSummary($attemptId)
    {
        // dd($attemptId);
        $attempt = QuizAttempt::with('details.question')->findOrFail($attemptId);
        return view('quiz.summary', compact('attempt'));
    }


}
