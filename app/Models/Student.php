<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'grade_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
