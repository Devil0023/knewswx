<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    use SoftDeletes;

    protected $table = 'exchange';
    protected $fillable = ["pid", "uid", "openid"];

    public function wxuser(){
        return $this->belongsTo(Wxuser::class, "uid", "id");
    }

}
