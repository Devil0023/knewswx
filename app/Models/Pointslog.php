<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointslog extends Model
{
    use SoftDeletes;

    protected $table = 'points_log';
    protected $fillable = ["uid", "openid", "points", "desc"];

    public function wxuser(){
        return $this->belongsTo(Wxuser::class,  "id", "uid");
    }
}
