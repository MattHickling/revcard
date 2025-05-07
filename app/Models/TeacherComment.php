<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherComment extends Model
{
    protected $fillable = [
            'teacher_id',
            'student_id',
            'comment',
        ];
}
