<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama.url', env('OLLAMA_API_URL'));
        $this->model = config('services.ollama.model', env('OLLAMA_MODEL'));
    }

  
    //   @param string $prompt
    //   @return array
    
    public function generateFlashcards(string $prompt): array
    {
        $response = Http::post("{$this->baseUrl}/models/{$this->model}/generate", [
            'prompt' => $prompt,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to communicate with Ollama API: ' . $response->body());
        }

        return $response->json();
    }
}
