<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function prizelist(){
        $now  = date("Y-m-d H:i:s");
        $list = Prize::where("checked", 1)->where("stime", "<=", $now)->where("etime", ">", $now)->get();

        var_dump($list);
    }

    public function prize($id){
        echo $id;
    }
}
