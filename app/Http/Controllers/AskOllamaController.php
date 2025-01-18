<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AskOllamaController extends Controller
{
    protected $askOllama;

    public function __construct(AskOllama $askOllama)
    {
        $this->askOllama = $askOllama;
    }

    /**
     * Generate flashcards from a user-provided topic.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
        ]);

        $topic = $request->input('topic');

        try {
            $flashcards = $this->ollamaService->generateFlashcards("Create flashcards for the topic: $topic");

            return response()->json([
                'success' => true,
                'data' => $flashcards,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

