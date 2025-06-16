<?php

use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StackController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ajax/search-schools', function(Request $request) {
    $query = $request->input('query');

    if (!$query) {
        return response()->json([]);
    }

    $schools = School::where('EstablishmentName', 'LIKE', "%{$query}%")
                     ->limit(10)
                     ->get(['id', 'EstablishmentName']);

    return response()->json($schools);
});

Route::get('/search-schools', [SchoolController::class, 'search'])->name('search.schools');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/associate-school', [ProfileController::class, 'associateSchool'])->name('associate.school');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::post('/teacher/comment', [TeacherController::class, 'comment'])->name('teacher.comment');

Route::post('/quiz/save', [QuizController::class, 'saveQuizResult'])->name('quiz.save');
Route::get('/quiz/summary/{stackId}', [QuizController::class, 'showQuizSummary'])->name('quiz.summary');

Route::get('/layouts.add-stack/{id}', [StackController::class, 'showForm'])->name('add-stack');
Route::post('/layouts.add-stack/{id}', [StackController::class, 'generateQuestion'])->name('generate-question');
Route::get('/stacks/{stack}', [StackController::class, 'show'])->name('view-stack');
Route::delete('/stacks/{id}', [StackController::class, 'destroy'])->name('delete-stack');

Route::get('/register/invite/{token}', [RegisteredUserController::class, 'showRegistrationForm'])->name('register.invited');
Route::post('/register/invite/{token}', [RegisteredUserController::class, 'register'])->name('register.invited.post');

Route::get('/admin/invite', [InviteController::class, 'create'])->name('admin.invite');
Route::post('/admin/invite', [InviteController::class, 'send'])->name('invites.send');

require __DIR__.'/auth.php';
