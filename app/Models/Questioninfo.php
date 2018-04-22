<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questioninfo extends Model
{
    use SoftDeletes;

    protected $table = 'questioninfo';

    protected $fillable = ["question", "type", "isrequired", "options", "qorder", "qid"];
}
