<?php

namespace App\Http\Controllers;

use App\Models\Getpoints;
use App\Models\Prize;
use App\Models\Wxuser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WechatController extends Controller
{

    public function index(){
        $wxuser = session("wxuser");
        return view("usercenter.index", compact("wxuser"));
    }

    public function pointstest($rid){
        $wxuser = session("wxuser");
        $result = Getpoints::getPointsByRule($wxuser["id"], $rid);
        var_dump($result);

        $new    = Wxuser::find($wxuser["id"]);
        var_dump($new->points);
    }

    public function updateUser(Request $request){
        $wxuser = session("wxuser");

        if(empty($request->address) || empty($request->mobile)){
            $message = array("error_code" => "400001", "error_message" => "存在参数为空");
        }else{

            $result  = Wxuser::find($wxuser["id"])->update(array(
                "address" => $request->address,
                "mobile"  => $request->mobile,
                "fill"    => 1,
            ));

            if($result){

                session(['wxuser.fill'    => 1]);
                session(['wxuser.mobile'  => $request->mobile]);
                session(['wxuser.address' => $request->address]);

                $message = array("error_code" => "0", "error_message" => "Success");
            }else{
                $message = array("error_code" => "400002", "error_message" => "资料失败");
            }
        }

        exit(json_encode($message));
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
