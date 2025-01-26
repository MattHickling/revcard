<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stack extends Model
{
    protected $fillable = [
        'stack_id',
        'year_in_school',
        'subject',
        'topic',
        'exam_board',
        'question_prompt',
    ];
}
