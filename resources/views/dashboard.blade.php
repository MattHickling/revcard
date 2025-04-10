{{-- Admin content --}}
@role('admin')
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
                {{ __('Your Flashcard Stacks') }}
            </h2>
        </x-slot>

        <div class="text-center">
            <a class="btn btn-success mt-2" href="{{ route('add-stack', ['id' => auth()->id()]) }}">Add A New Stack</a>
        </div>

        @if($openStacks->isNotEmpty())
            <div class="max-w-2xl mx-auto mt-4">
                <input type="text" id="searchInput" placeholder="Search flashcard stacks..." class="form-control p-2 border rounded">
            </div>
        @endif

        @if($openStacks->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach($openStacks as $stack)
                <div class="stack-card bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h5 class="text-xl font-bold mb-2">{{ $stack->subject }} - {{ $stack->topic }}</h5>
                    <p class="text-black">Year: {{ $stack->year_in_school }}</p>
                    <p class="text-black">Exam Board: {{ $stack->exam_board }}</p>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('view-stack', ['stack' => $stack->id]) }}" class="btn btn-primary">Take Quiz</a>

                        <form action="{{ route('delete-stack', ['id' => $stack->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stack?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Stack</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <div class="alert alert-info mt-4" role="alert">
                You have no open stacks. Click "Add A New Stack" to create one!
            </div>
        @endif

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-900">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let stacks = document.querySelectorAll('.stack-card');

            stacks.forEach(stack => {
                let text = stack.textContent.toLowerCase();
                if (text.includes(filter)) {
                    stack.style.display = "block";
                } else {
                    stack.style.display = "none";
                }
            });
        });
    </script>
@endrole

{{-- Teacher content --}}
{{-- @role('teacher') --}}
<x-app-layout>
    <h2 class="font-bold text-2xl text-gray-800 mb-4">Student Performance Overview</h2>

    {{-- Average Score per Stack --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">📊 Average Score per Topic</h3>
        <ul class="space-y-1">
            @foreach($avgByStack as $item)
                <li class="bg-white p-3 rounded shadow">
                    {{ $item->subject }} - {{ $item->topic }}: 
                    <strong>{{ $item->average_score }}%</strong>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Attempt History --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">📈 Recent Quiz Attempts</h3>
        <ul class="space-y-1">
            @foreach($attempts as $attempt)
                <li class="bg-white p-3 rounded shadow">
                    Attempt #{{ $attempt->attempt_number }} (Stack #{{ $attempt->stack_id }}): 
                    <strong>{{ round(($attempt->correct_answers / $attempt->total_questions) * 100, 1) }}%</strong> 
                    on {{ $attempt->created_at->format('M d, Y') }}
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Most Missed Questions --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">❌ Most Missed Questions</h3>
        <ul class="space-y-1">
            @foreach($commonMistakes as $mistake)
                <li class="bg-white p-3 rounded shadow">
                    "{{ $mistake->text }}" — missed <strong>{{ $mistake->times_wrong }}</strong> times
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Answer Breakdown --}}
    @if($latestAttempt && $answerBreakdown->count())
        <div>
            <h3 class="text-lg font-semibold mb-2">📝 Last Attempt (#{{ $latestAttempt->attempt_number }}) - Answer Breakdown</h3>
            <ul class="space-y-2">
                @foreach($answerBreakdown as $detail)
                    <li class="bg-white p-3 rounded shadow">
                        <p><strong>Q:</strong> {{ $detail->question->text }}</p>
                        <p>Answer: {{ $detail->user_answer }} | Correct: {{ $detail->correct_answer }}</p>
                        <p>
                            Result: 
                            {!! $detail->is_correct 
                                ? '<span class="text-green-600 font-bold">Correct</span>' 
                                : '<span class="text-red-600 font-bold">Wrong</span>' 
                            !!}
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-app-layout>

{{-- @endrole --}}

{{-- Student content --}}
@role('student')
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
                {{ __('Your Flashcard Stacks') }}
            </h2>
        </x-slot>
    
        <div class="text-center">
            <a class="btn btn-success mt-2" href="{{ route('add-stack', ['id' => auth()->id()]) }}">Add A New Stack</a>
        </div>
        @if($openStacks->isNotEmpty())
            <div class="max-w-2xl mx-auto mt-4">
                <input type="text" id="searchInput" placeholder="Search flashcard stacks..." class="form-control p-2 border rounded">
            </div>
        @endif
        @if($openStacks->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach($openStacks as $stack)
                <div class="stack-card bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h5 class="text-xl font-bold mb-2">{{ $stack->subject }} - {{ $stack->topic }}</h5>
                    <p class="text-black">Year: {{ $stack->year_in_school }}</p>
                    <p class="text-black">Exam Board: {{ $stack->exam_board }}</p>

                    
                    <div class="flex justify-between items-center">
                        <a href="{{ route('view-stack', ['stack' => $stack->id]) }}" class="btn btn-primary">Take Quiz</a>
                        
                        <form action="{{ route('delete-stack', ['id' => $stack->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stack?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Stack</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mt-4" role="alert">
            You have no open stacks. Click "Add A New Stack" to create one!
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    </x-app-layout>
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let stacks = document.querySelectorAll('.stack-card');

        stacks.forEach(stack => {
            let text = stack.textContent.toLowerCase();
            if (text.includes(filter)) {
                stack.style.display = "block";
            } else {
                stack.style.display = "none";
            }
        });
    });
</script>
@endrole
