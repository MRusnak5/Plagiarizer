<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{


    protected $connection = 'mysql2';

    public $table="mdl_quiz";

    use HasFactory;


    public function course()
    {
        return $this->belongsTo(Course::class,"id","course");
    }
    public function quiz_attempts()
    {
        return $this->hasMany(QuizAttempts::class,"id","quiz");
    }
}
