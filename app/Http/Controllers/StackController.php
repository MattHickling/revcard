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
        ]);
    
        $stack = Stack::create([
            'stack_id' => $id,
            'user_id' => auth()->user()->id,
            'year_in_school' => $request->input('year_in_school'),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'exam_board' => $request->input('exam_board'),
            'question_prompt' => $questionPrompt,
        ]);
    
        foreach ($generatedQuestions as $index => $questionText) {
            if ($index % 6 === 0) { 
                $questionSet = array_slice($generatedQuestions, $index, 6);
                $parsedQuestion = $this->parseQuestion($questionSet);
        
                Log::info('Parsed Question:', $parsedQuestion);
                if (empty($parsedQuestion['question'])) {
                    Log::error('Empty question detected. Skipping record.', ['question_set' => $questionSet]);
                    continue;
                }

                $stack->questions()->create([
                    'text' => $parsedQuestion['question'],
                    'option_1' => $parsedQuestion['option_1'],
                    'option_2' => $parsedQuestion['option_2'],
                    'option_3' => $parsedQuestion['option_3'],
                    'option_4' => $parsedQuestion['option_4'],
                    'correct_answer' => $parsedQuestion['correct_answer'],
                ]);
            }
        }
        
        return view('layouts.add-stack', [
            'id' => $id,
            'questionPrompt' => $questionPrompt,
            'generatedQuestions' => $generatedQuestions,
            'correct_answer' => $parsedQuestion['correct_answer'], 
        ]);
        
        
    }
    

    private function generateQuestionPrompt($request)
    {
        $prompt = "Please act as a {$request->input('year_in_school')} {$request->input('subject')} teacher and write 10 multiple-choice questions covering key stage 3 {$request->input('topic')} topics, aligned with the {$request->input('exam_board')} GCSE {$request->input('subject')} specification. Ensure the questions are of varying difficulty. Provide four answer options for each question, with one correct answer and three plausible distractors. This response cannot be more than 50 words. Can you identify the answers by adding A: B: C: D: infront of each multiple choice answer. And CORRECT ANSWER after the correct answer.";
        Log::info('Generated Question Prompt:', ['prompt' => $prompt]);
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


    private function parseQuestion($questionSet)
    {
        if (count($questionSet) < 6) {
            Log::error('Malformed question set detected', ['question_set' => $questionSet]);
            return [
                'question' => null,
                'option_1' => '',
                'option_2' => '',
                'option_3' => '',
                'option_4' => '',
                'correct_answer' => '',
            ];
        }
    
        $question = array_shift($questionSet);
    
        $options = array_slice($questionSet, 0, 4);
    
        $options = array_map(function($option) {
            return preg_replace('/^[A-D]:\s/', '', $option);
        }, $options);
    
        $correctAnswer = $this->getCorrectAnswer($questionSet);

        return [
            'question' => $question,
            'option_1' => $options[0] ?? '',
            'option_2' => $options[1] ?? '',
            'option_3' => $options[2] ?? '',
            'option_4' => $options[3] ?? '',
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

        return 'A';
    }







}

