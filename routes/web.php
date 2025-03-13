<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StackController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    
    Route::post('/quiz/save', [QuizController::class, 'saveQuizResult'])->name('quiz.save');
    Route::get('/quiz/summary/{stackId}', [QuizController::class, 'showQuizSummary'])->name('quiz.summary');


    Route::get('/layouts.add-stack/{id}', [StackController::class, 'showForm'])->name('add-stack');
    Route::post('/layouts.add-stack/{id}', [StackController::class, 'generateQuestion'])->name('generate-question');
    Route::get('/stacks/{stack}', [StackController::class, 'show'])->name('view-stack');
    Route::delete('/stacks/{id}', [StackController::class, 'destroy'])->name('delete-stack');
});


require __DIR__.'/auth.php';
