<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUsers extends Model
{
    protected $connection = 'mysql2';

    public $table="mdl_user";

    use HasFactory;

}
