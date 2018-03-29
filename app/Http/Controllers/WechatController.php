<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Getpoints;
use App\Models\Prize;
use App\Models\Wxmenu;
use App\Models\Wxuser;
use App\Models\Pointslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class WechatController extends Controller
{

    public function index(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();

        return view("usercenter.index", compact("wxuser"));
    }

    public function signpage(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();
        return view("usercenter.sign", compact("wxuser"));
    }
    
    public function sign(){
        $wxuser  = session("wxuser");
        $uid     = $wxuser["id"];
        $wxuser  = Wxuser::find($uid);

        $signkey = "KnewsWX-UserSign-".$wxuser->id;
        $signchk = @Redis::get($signkey);

        if(intval($signchk) === 1){
            $message = array("error_code" => "400008", "error_message" => "今日已签到");
        }else{

            $result = Getpoints::getPointsByRule($wxuser->id, 1);

            if($result){
                $length = strtotime(date("Y-m-d 23:59:59")) - time();
                @Redis::setex($signkey, $length, 1);

                $new     = Wxuser::find($uid);
                $message = array("error_code" => "0", "error_message" => "Success", "points" => $new->points);

            }else{
                $message = array("error_code" => "400009", "error_message" => "签到失败");
            }

        }

        exit(json_encode($message));
    }

    public function exchange($pid){

        $prize  = Prize::find($pid);
        $now    = time();
        $wxuser = session("wxuser");
        $uid    = $wxuser["id"];
        $wxuser = Wxuser::find($uid);

        $logkey = "KnewsWx-ExchangeLog-".$prize->id."-".$wxuser->id;
        $logchk = @Redis::get($logkey);

        if(empty($wxuser->address) || empty($wxuser->mobile)){
            $message = array("error_code" => "400011", "error_message" => "资料尚未完善");
        }elseif(strtotime($prize->stime) > $now || strtotime($prize->etime) < $now){
            $message = array("error_code" => "400003", "error_message" => "不在奖品兑换时间内");
        }elseif(intval($logchk) === 1){
            $message = array("error_code" => "400010", "error_message" => "同一奖品在半年内不得重复兑换");
        }else{

            $pop    = @Redis::rpop("WXPrizePoolList-".$pid);

            if(is_null($pop)){
                $message = array("error_code" => "400004", "error_message" => "奖品已被兑换完");
            }else{



                $result = Getpoints::getPoints($wxuser["id"], (0 - $prize->cost), "兑换奖品：".$prize->prize);

                if($result === 1){

                    $exchange_result = Exchange::create(array(
                        "pid" => $prize->id,
                        "uid" => $wxuser->id,
                        "openid" => $wxuser->openid,
                    ));

                    if($exchange_result){

                        @Redis::setex($logkey, 50, 1);

                        $new     = Wxuser::find($uid);
                        $message = array("error_code" => "0", "error_message" => "Success", "points" => $new->points);

                    }else{
                        $message = array("error_code" => "400007", "error_message" => "兑换信息保存失败，请联系管理员");
                    }


                }elseif($result === 0){
                    $message = array("error_code" => "400005", "error_message" => "积分余额不足");
                }else{
                    $message = array("error_code" => "400006", "error_message" => "兑换失败");
                }

            }

            if($message["error_code"] !== "0"){
                @Redis::lpush("WXPrizePoolList-".$pid, 1);
            }

        }



        exit(json_encode($message));
    }

    public function profile(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();

        session(['wxuser.profileprivious' => URL::previous()]);

        return view("usercenter.profile", compact("wxuser"));
    }

    public function detail(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();
        $key    = "KnewsWX-PointsLog-".$wxuser["id"];
        $json   = @Redis::get($key);

        if(empty($json)){
            $json   = Pointslog::where("uid", $wxuser["id"])->orderBy("created_at", "desc")->take(10)->get()->toJson();
            @Redis::setex($key, 3, $json);
        }

        $list   = json_decode($json, true);
        //return view("usercenter.index", compact("list"));
        //var_dump($list);
        return view("usercenter.detail", compact("wxuser","list"));
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

        if($message['error_code']==="0"){
            $previous = isset($wxuser["profileprivious"]) && !empty($wxuser["profileprivious"])? $wxuser["profileprivious"]: "/wechat/usercenter/index";
            echo "<script>alert(\"修改成功\");window.location.href=\"$previous\";</script>";
        }else{
            echo "<script>alert(\"".$message['error_message']."\");history.go(-1);</script>";
        }
        //exit(json_encode($message));
    }

    public function prizelist(){
        $wxuser = session("wxuser");
        $wxuser = Wxuser::find($wxuser["id"])->toArray();

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
            $list[$key]=$val;
        }
        return view("usercenter.prizeList", compact("wxuser","list"));
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
        return view("usercenter.prizeDetail", compact("prize"));
    }
}
