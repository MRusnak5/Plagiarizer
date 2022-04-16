<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Course extends Model
{

    protected $connection = 'mysql2';
    use HasFactory;


    public $table="mdl_course";


    public function quizes()
    {
        return $this->hasMany(Quiz::class,"course","id");
    }

    public function getEndDateAttribute($value)
    {
        $date = Carbon::createFromTimestamp($value)->format('d-m-Y H:i:s');

        return $date;
    }public function getStartDateAttribute($value)
    {
        $date = Carbon::createFromTimestamp($value)->format('d-m-Y H:i:s');

        return $date;
    }
}
