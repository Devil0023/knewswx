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
            Redis::setex($key, 60, $json);
            echo 111111;
        }

        $list = json_decode($json, true);
        foreach($list as $key => $val){
            echo "<a href=\"/wechat/prize/detail/{$val[id]}\">{$val[prize]}</a><br/>";
        }
    }

    public function prize($id){
        echo $id;
    }
}
