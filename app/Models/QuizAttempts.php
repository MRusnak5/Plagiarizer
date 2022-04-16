<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempts extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    public $table="mdl_quiz_attempts";


    public function attempts()
    {
        return $this->belongsTo(Quiz::class,"quiz","id");
    }
    public function quiz_users()
    {
        return $this->hasMany(QuizUsers::class,"id","userid");
    }


}
