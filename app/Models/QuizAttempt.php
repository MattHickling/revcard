<?php

namespace App\Models;

use App\Models\QuizAttemptDetail;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts'; 
    protected $fillable = [
        'user_id',
        'stack_id',
        'attempt_number',
        'correct_answers',
        'wrong_answers',
        'total_questions',
    ];

    public function details()
    {
        return $this->hasMany(QuizAttemptDetail::class);
    }
}
