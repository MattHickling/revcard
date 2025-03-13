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

    .answer.correct {
        color: green;
        font-weight: bold;
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
        <h3 class="text-center font-semibold text-xl text-gray-100 dark:text-gray-100 leading-tight">
            {{ $stack->subject }} - {{ $stack->topic }}
        </h3>
        <p class="text-center font-semibold text-xl text-gray-100 dark:text-gray-100 leading-tight">
            <strong>Year:</strong> {{ $stack->year_in_school }} | <strong>Exam Board:</strong> {{ $stack->exam_board }}
        </p>

        <div class="text-center mt-3">
            <a href="{{ route('dashboard', ['stack' => $stack->id]) }}" class="btn btn-secondary">Back to Stacks</a>
        </div>

        @if($stack->questions->isNotEmpty())
            <meta name="csrf-token" content="{{ csrf_token() }}">

            @foreach($stack->questions as $question)
                <div class="card mt-3">
                    <div class="card-body" data-question-id="{{ $question->id }}">
                        <h5 class="card-title"><strong>Question:</strong></h5>
                        <p>{{ $question->text }}</p>

                        <ul class="answer-list">
                            <li class="answer" data-answer="A" data-correct="{{ $question->correct_answer == 'A' ? 'true' : 'false' }}">
                                <strong>A:</strong> {{ $question->option_1 }}
                            </li>
                            <li class="answer" data-answer="B" data-correct="{{ $question->correct_answer == 'B' ? 'true' : 'false' }}">
                                <strong>B:</strong> {{ $question->option_2 }}
                            </li>
                            <li class="answer" data-answer="C" data-correct="{{ $question->correct_answer == 'C' ? 'true' : 'false' }}">
                                <strong>C:</strong> {{ $question->option_3 }}
                            </li>
                            <li class="answer" data-answer="D" data-correct="{{ $question->correct_answer == 'D' ? 'true' : 'false' }}">
                                <strong>D:</strong> {{ $question->option_4 }}
                            </li>
                        </ul>

                        <div class="correct-answer mt-3" style="display: none;">
                            ✅ Correct Answer: <strong>{{ $question->correct_answer }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-4">
                <button onclick="saveQuizResult()" class="btn btn-primary">Finish Quiz</button>
            </div>

        @else
            <div class="alert alert-info mt-3">No questions available for this stack.</div>
        @endif

    </div>
    <!-- Quiz Results Modal -->
<div class="modal fade" id="quizResultsModal" tabindex="-1" aria-labelledby="quizResultsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quizResultsModalLabel">Quiz Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Correct Answers:</strong> <span id="correctCount"></span></p>
                <p><strong>Wrong Answers:</strong> <span id="wrongCount"></span></p>
                <p><strong>Score:</strong> <span id="scorePercentage"></span>%</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('quiz.summary', ['stackId' => $stack->id]) }}"id="reviewSummaryBtn" class="btn btn-primary" href="#">Review Summary</a>
                {{-- <a href="{{ route('dashboard', ['stack' => $stack->id]) }}" --}}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        let answers = [];

        document.querySelectorAll('.answer').forEach(answer => {
            answer.addEventListener('click', function() {
                let cardBody = this.closest('.card-body');
                let questionId = cardBody.getAttribute('data-question-id');
                let userAnswer = this.getAttribute('data-answer');
                let correctAnswerElement = cardBody.querySelector('[data-correct="true"]');
                let correctAnswer = correctAnswerElement ? correctAnswerElement.getAttribute('data-answer') : null;

                let isCorrect = userAnswer === correctAnswer;

                answers.push({
                    question_id: questionId,
                    user_answer: userAnswer,
                    correct_answer: correctAnswer,
                    is_correct: isCorrect
                });

                cardBody.querySelectorAll('.answer').forEach(a => a.style.pointerEvents = 'none');

                if (isCorrect) {
                    this.classList.add('correct');
                } else {
                    this.classList.add('wrong');
                    correctAnswerElement.classList.add('correct');
                }
            });
        });

    function saveQuizResult() {
        if (answers.length === 0) {
            alert('Please answer at least one question before finishing the quiz.');
            return;
        }

        console.log("Sending the following data:", {
            stack_id: {{ $stack->id }},
            answers: answers
        });

        $.ajax({
            url: '/revcard/public/quiz/save',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                stack_id: {{ $stack->id }},
                answers: answers
            }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // console.log("Response from server:", data);
                $('#correctCount').text(data.correct);
                $('#wrongCount').text(data.wrong);
                
                const score = (data.correct / (data.correct + data.wrong)) * 100;
                $('#scorePercentage').text(score.toFixed(2));

                // const summaryUrl = "{{ route('quiz.summary', ':attemptId') }}".replace(':attemptId', data.attempt_id);
                // $('#reviewSummaryBtn').attr('href', summaryUrl);

                $('#quizResultsModal').modal('show');          
             },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                alert('An error occurred while saving your quiz. Check the console for details.');
            }
        });
}

</script>

</x-app-layout>
