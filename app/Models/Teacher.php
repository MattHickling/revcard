<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;



class Teacher extends Model
{
    
    protected $fillable = [
        'user_id',
        'department',
        'school_name_teacher'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
