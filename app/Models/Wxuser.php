<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wxuser extends Model
{
    use SoftDeletes;

    protected $table = 'wxuser';

    protected $fillable = ["address", "mobile", "fill"];
}
