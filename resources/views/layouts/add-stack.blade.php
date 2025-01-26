<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('What are you revising?') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('generate-question', ['id' => $id]) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="year_in_school" class="block text-sm font-medium text-gray-700">Year in School</label>
                            <select id="year_in_school" name="year_in_school" class="form-select mt-1 block w-full" required>
                                <option value="">Select Year</option>
                                @foreach(config('preferences.years_in_school') as $year)
                                    <option value="{{ $year }}" {{ old('year_in_school') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select id="subject" name="subject" class="form-select mt-1 block w-full" required>
                                <option value="">Select Subject</option>
                                @foreach(config('preferences.subjects') as $subject)
                                    <option value="{{ $subject }}" {{ old('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="topic" class="block text-sm font-medium text-gray-700">Topic</label>
                            <select id="topic" name="topic" class="form-select mt-1 block w-full" required>
                                <option value="">Select Topic</option>
                            </select>
                        </div>
                        

                        <div class="mb-4">
                            <label for="exam_board" class="block text-sm font-medium text-gray-700">Exam Board</label>
                            <select id="exam_board" name="exam_board" class="form-select mt-1 block w-full" required>
                                <option value="">Select Exam Board</option>
                                @foreach(config('preferences.exam_boards') as $examBoard)
                                    <option value="{{ $examBoard }}" {{ old('exam_board') == $examBoard ? 'selected' : '' }}>{{ $examBoard }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning">Generate Questions</button>
                    </form>
                    {{-- Just for testing --}}
                    @if(isset($questionPrompt))
                        <div>
                            <p>{{ $questionPrompt }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const topicsBySubject = {
            'Maths': @json(config('preferences.topics.maths')),
            'Science': @json(config('preferences.topics.science')),
            'History': @json(config('preferences.topics.history')),
            'Geography': @json(config('preferences.topics.geography')),
            'Computing & ICT': @json(config('preferences.topics.computing_ict')),
        };

        const subjectSelect = document.getElementById('subject');
        const topicSelect = document.getElementById('topic');

        subjectSelect.addEventListener('change', function () {
            topicSelect.innerHTML = '<option value="">Select Topic</option>';

            const selectedSubject = subjectSelect.value;

            if (selectedSubject && topicsBySubject[selectedSubject]) {
                topicsBySubject[selectedSubject].forEach(function (topic) {
                    const option = document.createElement('option');
                    option.value = topic;
                    option.textContent = topic;
                    topicSelect.appendChild(option);
                });
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const examBoardsBySubject = @json(config('preferences.exam_boards_by_subject'));

        const subjectSelect = document.getElementById('subject');
        const examBoardSelect = document.getElementById('exam_board');

        subjectSelect.addEventListener('change', function () {
            examBoardSelect.innerHTML = '<option value="">Select Exam Board</option>';

            const selectedSubject = subjectSelect.value;

            if (selectedSubject && examBoardsBySubject[selectedSubject]) {
                examBoardsBySubject[selectedSubject].forEach(function (board) {
                    const option = document.createElement('option');
                    option.value = board;
                    option.textContent = board;
                    examBoardSelect.appendChild(option);
                });
            }
        });
    });
</script>
