<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-900 leading-tight">
            {{ __('Flashcards for ') . $stack->subject . ' - ' . $stack->topic }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <h3 class="text-center font-semibold text-xl text-gray-100 dark:text-gray-100 leading-tight">{{ $stack->subject }} - {{ $stack->topic }}</h3>
        <p class="text-center font-semibold text-xl text-gray-100 dark:text-gray-100 leading-tight"><strong>Year:</strong> {{ $stack->year_in_school }} | <strong>Exam Board:</strong> {{ $stack->exam_board }}</p>

        <div class="text-center mt-3">
            <a href="{{ route('dashboard', ['stack' => $stack->id]) }}" class="btn btn-secondary">Back to Stacks</a>
        </div>

        @if($stack->questions->isNotEmpty())
            @foreach($stack->questions as $question)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $question->text }}</h5>
                        <ul>
                            <li>{{ $question->option_1 }}</li>
                            <li>{{ $question->option_2 }}</li>
                            <li>{{ $question->option_3 }}</li>
                            <li>{{ $question->option_4 }}</li>
                        </ul>
                        <strong>Correct Answer: {{ $question->correct_answer }}</strong>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info mt-3">No questions available for this stack.</div>
        @endif
    </div>
</x-app-layout>
