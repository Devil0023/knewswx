<?php

namespace App\Http\Middleware;

use App\Models\Wxuser;
use EasyWeChat\Factory;
use Symfony\Component\Cache\Simple\RedisCache;
use Closure;

class KnewsRegister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userinfo = session("wechat.oauth_user");
        $original = $userinfo["default"]->original;
        $wxuser   = Wxuser::where("openid", "=", $original["openid"])->first();



        if($original["nickname"] === "Z.L"){
            $app      = Factory::officialAccount(config("wechat.official_account.default"));
            $accessToken = $app->access_token;
            $token = $accessToken->getToken(); // token 数组  token['access_token'] 字符串
            print_r($token);
        }

        if(is_null($wxuser)){
            $newuser = new Wxuser();
            $newuser->openid     = $original["openid"];
            $newuser->nickname   = $original["nickname"];
            $newuser->sex         = $original["sex"];
            $newuser->language   = $original["language"];
            $newuser->province   = $original["province"];
            $newuser->city        = $original["city"];
            $newuser->country    = $original["country"];
            $newuser->headimgurl = $original["headimgurl"];
            $newuser->privilege  = json_encode($original["privilege"]);
            $newuser->unionid    = isset($original["unionid"])? $original["unionid"]: "";
            $newuser->fill        = 0;
            $newuser->points      = 0;

            $newuser->save();

            session(['wxuser.id'         => $newuser->id]);
            session(['wxuser.fill'      => $newuser->fill]);
            session(['wxuser.mobile'    => $newuser->mobile]);
            session(['wxuser.address'   => $newuser->address]);
            session(['wxuser.nickname'  => $newuser->nickname]);
            session(['wxuser.headimgurl'=> $newuser->headimgurl]);
            session(['wxuser.points'     => $newuser->points]);

        }else{

            session(['wxuser.id'        => $wxuser->id]);
            session(['wxuser.fill'      => $wxuser->fill]);
            session(['wxuser.mobile'    => $wxuser->mobile]);
            session(['wxuser.address'   => $wxuser->address]);
            session(['wxuser.nickname'  => $wxuser->nickname]);
            session(['wxuser.headimgurl'=> $wxuser->headimgurl]);
            session(['wxuser.points'     => $wxuser->points]);

        }

        return $next($request);
    }
}
