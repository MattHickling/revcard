<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stack;  

class StackController extends Controller
{
    public function generateQuestion(Request $request, $id)
    {
        $user = User::find(1);
        $user->assignRole('Admin');
        $user->givePermissionTo('edit users');
        
        $request->validate([
            'year_in_school' => 'required|string',
            'subject' => 'required|string',
            'topic' => 'required|string',
            'exam_board' => 'required|string',
        ]);

        $questionPrompt = $this->generateQuestionPrompt($request);
// dd(auth()->user()->id);
        Stack::create([
            'stack_id' => $id,  
            'user_id' => auth()->user()->id,  
            'year_in_school' => $request->input('year_in_school'),
            'subject' => $request->input('subject'),
            'topic' => $request->input('topic'),
            'exam_board' => $request->input('exam_board'),
            'question_prompt' => $questionPrompt,
        ]);

        return view('layouts.add-stack', compact('id', 'questionPrompt'));
    }

    private function generateQuestionPrompt($data)
    {
        return "Please act as a {$data->year_in_school} {$data->subject} teacher and write 10 multiple-choice questions covering key stage 3 {$data->topic} topics, aligned with the {$data->exam_board} GCSE {$data->subject} specification. Ensure the questions are of varying difficulty. Provide four answer options for each question, with one correct answer and three plausible distractors.";
    }
    
    public function showForm($id)
    {
        return view('layouts.add-stack', ['id' => $id]);
    }

    public function show($id)
    {
        $stack = Stack::with('questions')->findOrFail($id);

        return view('stacks.show', compact('stack'));
    }
    


}

