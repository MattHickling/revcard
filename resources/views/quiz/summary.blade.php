@extends('layouts.app')

<x-slot name="content">
    <div class="container">
        <h1>Quiz Summary</h1>

        <div class="row mb-4">
            <div class="col-md-6">
                <h3>Quiz Attempt Information</h3>
                <p><strong>Attempt ID:</strong> {{ $attempt->id }}</p>
                <p><strong>Total Questions:</strong> {{ $attempt->details->count() }}</p>
                <p><strong>Correct Answers:</strong> {{ $attempt->details->where('is_correct', true)->count() }}</p>
                <p><strong>Incorrect Answers:</strong> {{ $attempt->details->where('is_correct', false)->count() }}</p>
                <p><strong>Score:</strong> {{ (100 * $attempt->details->where('is_correct', true)->count()) / $attempt->details->count() }}%</p>
            </div>
        </div>

        <h3>Question Details</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct Answer</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempt->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->question->text }}</td>
                        <td>{{ $detail->user_answer }}</td>
                        <td>{{ $detail->correct_answer }}</td>
                        <td>
                            @if($detail->is_correct)
                                <span class="badge badge-success">Correct</span>
                            @else
                                <span class="badge badge-danger">Incorrect</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ url('/quiz') }}" class="btn btn-primary mt-4">Back to Quiz List</a>
    </div>
</x-slot>
