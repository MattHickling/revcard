<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Flashcard Stacks') }}
        </h2>
    </x-slot>

<div class="text-center">
    <a class="btn btn-success mt-2" href="{{ route('add-stack', ['id' => auth()->id()]) }}">Add A New Stack</a>
</div>


    @if($openStacks->isNotEmpty())
        @foreach($openStacks as $stack)
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $stack->subject }} - {{ $stack->topic }}</h5>
                    <p class="card-text">Year: {{ $stack->year_in_school }}</p>
                    <p class="card-text">Exam Board: {{ $stack->exam_board }}</p>
                    <a href="{{ route('view-stack', ['stack' => $stack->id]) }}" class="btn btn-primary">View Stack</a>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info mt-4" role="alert">
            You have no open stacks. Click "Add A New Stack" to create one!
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
