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

        $json = Redis::get($key);
        if($json){
            $list = json_decode($json, true);
        }else{
            $now  = date("Y-m-d H:i:s", $time);
            $list = Prize::where("checked", 1)->where("stime", "<=", $now)->where("etime", ">", $now)->get()->toJson();
            exit($list); die;
        }


        var_dump($list);
    }

    public function prize($id){
        echo $id;
    }
}
