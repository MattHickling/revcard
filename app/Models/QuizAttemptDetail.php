<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptDetail extends Model
{
    protected $table = 'quiz_attempts_details'; 
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'user_answer',
        'correct_answer',
        'is_correct',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    
}
