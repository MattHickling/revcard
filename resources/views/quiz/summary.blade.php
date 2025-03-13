<x-layout>
    <div class="container text-white">
        <h1>Quiz Summary</h1>

        @php
            $totalQuestions = $attempt->details->count();
            $correctAnswersCount = $attempt->details->where('is_correct', true)->count();
            $incorrectAnswers = $totalQuestions - $correctAnswersCount;
        @endphp

        <div class="row mb-4">
            <div class="col-md-6">
                <h3>Quiz Attempt Information</h3>
                <p><strong>Attempt ID:</strong> {{ $attempt->id }}</p>
                <p><strong>Total Questions:</strong> {{ $totalQuestions }}</p>
                <p><strong>Correct Answers:</strong> {{ $correctAnswersCount }}</p>
                <p><strong>Incorrect Answers:</strong> {{ $incorrectAnswers }}</p>
                <p><strong>Score:</strong> {{ $totalQuestions > 0 ? (100 * $correctAnswersCount) / $totalQuestions : 0 }}%</p>
            </div>
        </div>

        <h3>Question Details</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-white">#</th>
                    <th class="text-white">Question</th>
                    <th class="text-white">Your Answer</th>
                    <th class="text-white">Correct Answer</th>
                    <th class="text-white">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempt->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($detail->question)->text ?? 'Question not found' }}</td>
                        <td>{{ $detail->user_answer }}</td>
                        <td>
                            @if (!$detail->is_correct)
                                <span class="text-warning">
                                    {{ $correctAnswers[$detail->question_id] ?? 'Answer not available' }}
                                </span>
                            @else
                                <span class="text-success">✔</span>
                            @endif
                        </td>
                        <td>
                            @if ($detail->is_correct)
                                <span class="badge bg-success">Correct</span>
                            @else
                                <span class="badge bg-danger">Incorrect</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form action="{{ route('delete-stack', ['id' => $stackId]) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this stack?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Stack</button>
        </form>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-4">Back to Dashboard</a>
        </div>
    </div>
</x-layout>
