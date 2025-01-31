<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['stack_id', 'text', 'option_1', 'option_2', 'option_3', 'option_4', 'correct_answer'];

    public function stack()
    {
        return $this->belongsTo(Stack::class);
    }
}
