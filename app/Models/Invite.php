<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = ['email', 'school_id', 'role', 'token', 'expires_at'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
