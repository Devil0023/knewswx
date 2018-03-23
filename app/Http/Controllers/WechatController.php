<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Getpoints;
use App\Models\Prize;
use App\Models\Wxuser;
use App\Models\Pointslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WechatController extends Controller
{

    public function index(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();

        return view("usercenter.index", compact("wxuser"));
    }

    public function pointstest(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();
//        //$result = Getpoints::getPointsByRule($wxuser["id"], $rid);
//        //var_dump($result);
//
//        $result = Getpoints::getPoints($wxuser["id"], -5, "兑换积分");
//        var_dump($result);
//
//        $new    = Wxuser::find($wxuser["id"]);
//        var_dump($new->points);
        return view("usercenter.index", compact("wxuser"));
    }

    public function exchange($pid){

        $prize  = Prize::find($pid);
        $now    = time();

        if(strtotime($prize->stime) > $now || strtotime($prize->etime) < $now){
            $message = array("error_code" => "400003", "error_message" => "不在奖品兑换时间内");
        }else{

            $pop    = @Redis::rpop("WXPrizePoolList-".$pid);
            if(is_null($pop)){
                $message = array("error_code" => "400004", "error_message" => "奖品已被兑换完");
            }else{

                $wxuser = session("wxuser");
                $wxuser = Wxuser::find($wxuser["id"]);

                $result = Getpoints::getPoints($wxuser["id"], (0 - $prize->cost), "兑换奖品：".$prize->prize);

                if($result === 1){

                    $exchange_result = Exchange::save(array(
                        "pid" => $prize->id,
                        "uid" => $wxuser->id,
                        "openid" => $wxuser->openid,
                    ));

                    if($exchange_result){
                        $message = array("error_code" => "0", "error_message" => "Success");
                    }else{
                        $message = array("error_code" => "400007", "error_message" => "兑换信息保存失败，请联系管理员");
                    }


                }elseif($result === 0){
                    $message = array("error_code" => "400005", "error_message" => "积分余额不足");
                }else{
                    $message = array("error_code" => "400006", "error_message" => "兑换失败");
                }

            }

        }

        exit(json_encode($message));
    }

    public function detail(){
        $wxuser = session("wxuser");

        $key    = "KnewsWX-PointsLog-".$wxuser["id"];
        $json   = @Redis::get($key);

        if(empty($json)){
            $json   = Pointslog::where("uid", $wxuser["id"])->orderBy("created_at", "desc")->take(10)->get()->toJson();
            @Redis::setex($key, 3, $json);
        }

        $list   = json_decode($json, true);

        var_dump($list);
    }

    public function updateUser(Request $request){
        $wxuser = session("wxuser");

        if(empty($request->address) || empty($request->mobile)){
            $message = array("error_code" => "400001", "error_message" => "存在参数为空");
        }else{

            $model   = Wxuser::find($wxuser["id"]);
            $fillchk = $model->fill;

            $result  = $model->update(array(
                "address" => $request->address,
                "mobile"  => $request->mobile,
                "fill"    => 1,
            ));

            if($result){

                if(intval($fillchk) === 0){  //第一次补完数据
                    Getpoints::getPointsByRule($wxuser["id"], 2);
                }

                session(['wxuser.fill'    => 1]);
                session(['wxuser.mobile'  => $request->mobile]);
                session(['wxuser.address' => $request->address]);

                $message = array("error_code" => "0", "error_message" => "Success");
            }else{
                $message = array("error_code" => "400002", "error_message" => "完善资料失败");
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
            $json = Prize::where("checked", 1)->where("stime", "<=", $now)->where("etime", ">", $now)
                    ->where("num", ">", 0)->orderBy("etime", "asc")->get()->toJson();

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
