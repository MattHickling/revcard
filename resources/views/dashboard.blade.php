{{-- Admin content --}}
@if(isset($role) && $role == 'admin')
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
        </x-slot>

        <div class="flex justify-center py-4">
            <a href="{{ route('admin.invite') }}"
               class="bg-white text-gray-900 font-bold py-2 px-6 rounded-lg shadow hover:bg-gray-100 transition">
                Send User Invite
            </a>
        </div>
        
    </x-app-layout>
@endif



{{-- @role('teacher') --}}
@if(isset($role) && $role == 'teacher')
<x-app-layout>
    <h2 class="font-bold text-2xl text-gray-800 mb-6">Student Performance Overview</h2>
    @foreach($students as $student)
        <div class="mb-4 bg-white p-6 rounded-lg shadow pb-4">
            <h3 class="text-2xl font-bold text-blue-700 mb-6">{{ $student->full_name }}</h3>

            {{-- Recent Attempts --}}
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Recent Attempts</h4>
                @forelse($student->quizAttempts->take(5) as $attempt)
                    <div class="bg-gray-100 p-4 rounded mb-2">
                        Attempt #{{ $attempt->attempt_number }} -
                        Score: <strong>{{ round(($attempt->correct_answers / $attempt->total_questions) * 100, 1) }}%</strong> 
                        on {{ $attempt->created_at->format('M d, Y') }}
                    </div>
                @empty
                    <p class="text-gray-500">No attempts found.</p>
                @endforelse
            </div>

            {{-- Last Attempt Breakdown --}}
            @if($student->latestQuizAttempt && $student->latestQuizAttempt->details->count())
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">Last Attempt Breakdown</h4>
                    <ul class="space-y-3">
                        @foreach($student->latestQuizAttempt->details as $detail)
                            <li class="bg-gray-100 p-4 rounded">
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

            {{-- Comment Box --}}
            <div class="mt-6">
                <form method="POST" action="{{ route('teacher.comment') }}">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <label for="comment-{{ $student->id }}" class="block mb-2 font-semibold text-gray-700">Teacher Comment:</label>
                    <textarea id="comment-{{ $student->id }}" name="comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ $student->teacherComment->comment ?? '' }}</textarea>
                    <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-black px-4 py-2 rounded">
                        Save Comment
                    </button>
                </form>
            </div>
        </div>
@endforeach

</x-app-layout>

@endif




{{-- Student --}}
@if(isset($role) && $role == 'student')
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
                {{ __('Your Flashcard Stacks') }}
            </h2>
        </x-slot>
    
        <div class="text-center">
            <a class="btn btn-success mt-2" href="{{ route('add-stack', ['id' => auth()->id()]) }}">Add A New Stack</a>
        </div>
        @if(isset($openStacks))
            @if($openStacks->isNotEmpty())
                <div class="max-w-2xl mx-auto mt-4">
                    <input type="text" id="searchInput" placeholder="Search flashcard stacks..." class="form-control p-2 border rounded">
                </div>
            @endif
        @endif
        @if(isset($openStacks))
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
        @endif
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
@endif
