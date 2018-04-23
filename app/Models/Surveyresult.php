<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surveyresult extends Model
{
    use SoftDeletes;

    protected $table = 'surveyresult';

    protected $fillable = ["code", "qid", "questionaire"];
}
