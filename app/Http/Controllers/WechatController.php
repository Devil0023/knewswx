<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WechatController extends Controller
{

    public function prizelist(){

        $time = time();
        $key  = "KnewsWX-Prizelist-".date("YmdHi", $time);
        $json = @Redis::get($key);

        if(empty($json)){
            $now  = date("Y-m-d H:i:s", $time);
            $json = Prize::where("checked", 1)->where("stime", "<=", $now)->where("etime", ">", $now)->get()->toJson();
            Redis::set($key, $json, 60);
            echo 111111;
        }

        $list = json_decode($json, true);
        var_dump($list);
    }

    public function prize($id){
        echo $id;
    }
}
