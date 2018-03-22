<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WechatController extends Controller
{

    public function index(){

        $wxuser = session("wxuser");

        var_dump($wxuser);


    }

    public function prizelist(){

        $time = time();
        $key  = "KnewsWX-Prizelist-".date("YmdHi", $time);
        $json = @Redis::get($key);

        if(empty($json)){
            $now  = date("Y-m-d H:i:s", $time);
            $json = Prize::where("checked", 1)->where("stime", "<=", $now)->where("etime", ">", $now)->where("num", ">", 0)->get()->toJson();
            Redis::setex($key, 60, $json);
        }

        $list = json_decode($json, true);
        foreach($list as $key => $val){
            $val["left"] = Redis::llen("WXPrizePoolList-".$val["id"]);
            echo "<a href=\"/wechat/prize/detail/".$val["id"]."\">".$val["prize"]."----剩余：".$val["left"]."----</a><br/>";
        }
    }

    public function prize($id){

        $key  = "KnewsWX-PrizeDetail-".$id;
        $json = @Redis::get($key);

        if(empty($json)){
            $json = Prize::find($id)->toJson();
            Redis::setex($key, 60, $json);
        }

        $prize = json_decode($json, true);
        $prize["left"] = Redis::llen("WXPrizePoolList-".$prize["id"]);

        var_dump($prize);
    }
}
