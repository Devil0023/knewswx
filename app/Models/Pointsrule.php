<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointsrule extends Model
{
    use SoftDeletes;

    protected $table = 'points_rule';
}
