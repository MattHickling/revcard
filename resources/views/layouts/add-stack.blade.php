<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
            {{ __('What are you revising?') }}
        </h2>
    </x-slot>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('generate-question', ['id' => $id]) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">How many questions</label>
                            <select id="quantity" name="quantity" class="form-select mt-1 block w-full" required>
                                <option value="">How many questions would you like?</option>
                                @foreach(config('preferences.quantity') as $quantity)
                                    <option value="{{ $quantity }}" {{ old('quantity') == $quantity ? 'selected' : '' }}>{{ $quantity }}</option>
                                @endforeach
                            </select>
                        </div>

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
            'English Literature': @json(config('preferences.topics.english_literature')),
            'English Language': @json(config('preferences.topics.english_language')),
            'Maths': @json(config('preferences.topics.maths')),
            'Biology': @json(config('preferences.topics.biology')),
            'Chemistry': @json(config('preferences.topics.chemistry')),
            'Physics': @json(config('preferences.topics.physics')),
            'History': @json(config('preferences.topics.history')),
            'Geography': @json(config('preferences.topics.geography')),
            'Spanish': @json(config('preferences.topics.spanish')),
            'French': @json(config('preferences.topics.french')),
            'German': @json(config('preferences.topics.german')),
            'Italian': @json(config('preferences.topics.italian')),
            'Art': @json(config('preferences.topics.art')),
            'Design Technology': @json(config('preferences.topics.design_technology')),
            'Physical Education': @json(config('preferences.topics.physical_education')),
            'Religious Studies': @json(config('preferences.topics.religious_studies')),
            'Music': @json(config('preferences.topics.music')),
            'Drama': @json(config('preferences.topics.drama')),
            'Business Studies': @json(config('preferences.topics.business_studies')),
            'Psychology': @json(config('preferences.topics.psychology')),
            'Sociology': @json(config('preferences.topics.sociology')),
            'Philosophy': @json(config('preferences.topics.philosophy')),
            'Economics': @json(config('preferences.topics.economics')),
            'Health & Social Care': @json(config('preferences.topics.health_and_social_care')),
            'ICT': @json(config('preferences.topics.ict')),
            'Media Studies': @json(config('preferences.topics.media_studies')),
            'Food Technology': @json(config('preferences.topics.food_technology')),
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
