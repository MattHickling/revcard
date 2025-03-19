<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stack;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class StackController extends Controller
{
    public function generateQuestion(Request $request, $id)
    {
        $user = User::find(1);
        // Log::info('Assigning roles and permissions to user', ['user_id' => $user->id]);
        // $user->assignRole('Admin');
        $user->givePermissionTo('edit users');

        $request->validate([
            'year_in_school' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',
            'exam_board' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $questionPrompt = $this->generateQuestionPrompt($request);

        $apiUrl = 'https://api.openai.com/v1/chat/completions';  
        $apiKey = env('OPEN_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $questionPrompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 3000, 
        ]);

        // if (!$response->successful()) {
        //     Log::error('Failed to generate questions', ['status' => $response->status(), 'body' => $response->body()]);
        //     return back()->withErrors(['error' => 'Failed to generate questions']);
        // }

        $rawResponse = $response->json();
        // Log::info('OpenAI API Response:', $rawResponse);

        $responseContent = $rawResponse['choices'][0]['message']['content'];
        $cleanQuestions = $this->extractQuestions($responseContent);

        // if (empty($cleanQuestions)) {
        //     return back()->withErrors(['error' => 'No questions generated.']);
        // }

        $stack = Stack::create([
            'stack_id' => $id,
            'user_id' => auth()->user()->id,
            'year_in_school' => $request->input('year_in_school'),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'exam_board' => $request->input('exam_board'),
            'question_prompt' => $questionPrompt,
            'quantity' => $request->input('quantity'),
        ]);

        foreach ($cleanQuestions as $questionSet) {
            $parsedQuestion = $this->parseQuestion($questionSet);

            // if (empty($parsedQuestion['question'])) {
            //     Log::error('Invalid question set. Skipping.', ['question_set' => $questionSet]);
            //     continue;
            // }

            $stack->questions()->create([
                'text' => $parsedQuestion['question'],
                'option_1' => $parsedQuestion['option_1'],
                'option_2' => $parsedQuestion['option_2'],
                'option_3' => $parsedQuestion['option_3'],
                'option_4' => $parsedQuestion['option_4'],
                'correct_answer' => $parsedQuestion['correct_answer'],
            ]);
        }
        $openStacks = Stack::where('user_id', auth()->id())->get();

        return view('dashboard', [
            'id' => $id,
            'questionPrompt' => $questionPrompt,
            'generatedQuestions' => $cleanQuestions,
            'openStacks' => $openStacks,
        ]);

    }

    private function generateQuestionPrompt($request)
    {
        $prompt = "Please act as a {$request->input('year_in_school')} {$request->input('subject')} teacher and write exactly {$request->input('quantity')} multiple-choice questions covering key stage 3 {$request->input('topic')} topics, aligned with the {$request->input('exam_board')} GCSE {$request->input('subject')} specification.

        Format the response like this, with no extra text:
        ### START QUESTION ###
        QUESTION: [The question text]  
        A: [Option A]  
        B: [Option B]  
        C: [Option C]  
        D: [Option D]  
        CORRECT ANSWER: [A/B/C/D]  
        ### END QUESTION ###";

        // Log::info('Generated Question Prompt:', ['prompt' => $prompt]);

        return $prompt;
    }

    public function showForm($id)
    {
        return view('layouts.add-stack', ['id' => $id]);
    }

    public function show($id)
    {
        $stack = Stack::with('questions')->findOrFail($id);
        // dd($stack);

        return view('stacks.show', compact('stack'));
    }

    private function extractQuestions($responseContent)
    {
        preg_match_all('/### START QUESTION ###(.*?)### END QUESTION ###/s', $responseContent, $matches);

        if (empty($matches[1])) {
            Log::error('No questions matched');
            return [];
        }

        $questions = array_map('trim', $matches[1]);
        Log::info('Extracted Questions:', ['questions' => $questions]);

        return $questions;
    }




    private function parseQuestion($questionSet)
    {
        $question = '';
        $options = [];
        $correctAnswer = '';

        foreach (explode("\n", $questionSet) as $line) {
            if (preg_match('/^QUESTION:\s*(.+)$/', $line, $matches)) {
                $question = $matches[1];
            } elseif (preg_match('/^[A-D]:\s*(.+)$/', $line, $matches)) {
                $options[] = $matches[1];
            } elseif (preg_match('/^CORRECT ANSWER:\s*([A-D])$/', $line, $matches)) {
                $correctAnswer = $matches[1];
            }
        }

        if (count($options) !== 4 || empty($question) || empty($correctAnswer)) {
            // Log::error('Invalid question format detected', ['question_set' => $questionSet]);
            return [
                'question' => null,
                'option_1' => '',
                'option_2' => '',
                'option_3' => '',
                'option_4' => '',
                'correct_answer' => '',
            ];
        }

        return [
            'question' => $question,
            'option_1' => $options[0],
            'option_2' => $options[1],
            'option_3' => $options[2],
            'option_4' => $options[3],
            'correct_answer' => $correctAnswer,
        ];
    }


    
    private function getCorrectAnswer($questionSet)
    {
        foreach ($questionSet as $line) {
            if (preg_match('/^CORRECT ANSWER:\s([A-D])/', $line, $matches)) {
                return $matches[1]; 
            }
        }

        return null;
    }

    public function destroy($id)
    {
        $stack = Stack::where('id', $id);

        $stack->delete();
        return redirect()->route('dashboard')->with('success', 'Stack deleted successfully!');
        
    }
}

