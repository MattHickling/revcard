<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stack extends Model
{
    protected $fillable = [
        'stack_id',
        'user_id',
        'year_in_school',
        'subject',
        'topic',
        'exam_board',
        'open',
        'question_prompt',
        'quantity'
    ];
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
