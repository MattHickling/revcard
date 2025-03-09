<style>
    .answer {
        cursor: pointer;
        padding: 5px;
        border: 1px solid #ddd;
        margin-bottom: 5px;
        text-align: left;
    }

    .answer:hover {
        background-color: #f0f0f0;
    }

    .correct-answer {
        color: green;
        font-weight: normal;
    }

    .answer.wrong {
        color: red;
        font-weight: bold;
    }

    .card-body {
        background-color: #f9f9f9; 
        padding: 15px;
    }
</style>

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
                            <li class="answer" data-answer="A" data-correct="{{ $question->correct_answer == 'A' ? 'true' : 'false' }}">{{ $question->option_1 }}</li>
                            <li class="answer" data-answer="B" data-correct="{{ $question->correct_answer == 'B' ? 'true' : 'false' }}">{{ $question->option_2 }}</li>
                            <li class="answer" data-answer="C" data-correct="{{ $question->correct_answer == 'C' ? 'true' : 'false' }}">{{ $question->option_3 }}</li>
                            <li class="answer" data-answer="D" data-correct="{{ $question->correct_answer == 'D' ? 'true' : 'false' }}">{{ $question->option_4 }}</li>
                        </ul>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info mt-3">No questions available for this stack.</div>
        @endif
    </div>

    <script>
       document.querySelectorAll('.answer').forEach(answer => {
            answer.addEventListener('click', function() {
                let cardBody = this.closest('.card-body');
                let answers = cardBody.querySelectorAll('.answer');
                
                answers.forEach(a => a.style.pointerEvents = 'none');  

                if (this.getAttribute('data-correct') === 'true') {
                    this.style.color = 'green';
                    this.style.fontWeight = 'bold';
                } else { 
                    this.style.color = 'red';
                    this.style.fontWeight = 'bold';
                }

                let correctAnswer = cardBody.querySelector('[data-correct="true"]');
                correctAnswer.style.color = 'green';
                correctAnswer.style.fontWeight = 'bold';
            });
        });
    </script>
</x-app-layout>
