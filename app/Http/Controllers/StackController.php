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
        // $user = User::find($id);
        Log::info('Assigning roles and permissions to user', ['user_id' => $user->id]);
        $user->assignRole('Admin');
        $user->givePermissionTo('edit users');
        
        $request->validate([
            'year_in_school' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',
            'exam_board' => 'required|string',
        ]);

        $questionPrompt = $this->generateQuestionPrompt($request);

        $apiUrl = 'https://api.openai.com/v1/completions';
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
            'max_tokens' => 300,  
        ]);

        if ($response->successful()) {
            $rawResponse = $response->json();
            Log::info('OpenAI API Response:', $rawResponse); 
            $generatedQuestions = explode("\n", $rawResponse['choices'][0]['message']['content']);
            $generatedQuestions = array_map('trim', $generatedQuestions);
            $generatedQuestions = array_filter($generatedQuestions);
        
            if (empty($generatedQuestions)) {
                return back()->withErrors(['error' => 'No questions generated. Please try again.']);
            }
        } else {
            Log::error('Failed to generate questions', ['status' => $response->status(), 'body' => $response->body()]);
            return back()->withErrors(['error' => 'Failed to generate questions']);
        }

        Log::info('Data being saved to the database:', [
            'stack_id' => $id,
            'user_id' => auth()->user()->id,
            'year_in_school' => $request->input('year_in_school'),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'exam_board' => $request->input('exam_board'),
            'question_prompt' => $questionPrompt,
            'generated_questions' => $generatedQuestions,
        ]);
        
        

// dd(auth()->user()->id);
        Stack::create([
            'stack_id' => $id,
            'user_id' => auth()->user()->id,
            'year_in_school' => $request->input('year_in_school'),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'exam_board' => $request->input('exam_board'),
            'question_prompt' => $questionPrompt,
            'generated_questions' => $generatedQuestions, 
        ]);

        return view('layouts.add-stack', compact('id', 'questionPrompt', 'generatedQuestions'));

    }

    private function generateQuestionPrompt($data)
    {
        // dd($data);
        // return "Please act as a {$data->year_in_school} {$data->subject} teacher and write 10 multiple-choice questions covering key stage 3 {$data->topic} topics, aligned with the {$data->exam_board} GCSE {$data->subject} specification. Ensure the questions are of varying difficulty. Provide four answer options for each question, with one correct answer and three plausible distractors.";
        $response = "Please act as a {$data->year_in_school} {$data->subject} teacher and write 10 multiple-choice questions covering key stage 3 {$data->topic} topics, aligned with the {$data->exam_board} GCSE {$data->subject} specification. Ensure the questions are of varying difficulty. Provide four answer options for each question, with one correct answer and three plausible distractors.";
        dd($response);  
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
    


}

