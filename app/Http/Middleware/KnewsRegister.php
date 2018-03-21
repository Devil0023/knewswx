<?php

namespace App\Http\Middleware;

use App\Models\Wxuser;
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
        $wxuser   = Wxuser::where("openid", "=", $userinfo["default"]->openid)->first();

        if($wxuser->id){
            var_dump(1);
        }else{
            $newuser = new Wxuser();
            $newuser->openid     = $userinfo["default"]->openid;
            $newuser->nickname   = $userinfo["default"]->nickname;
            $newuser->sex         = $userinfo["default"]->sex;
            $newuser->province   = $userinfo["default"]->province;
            $newuser->city        = $userinfo["default"]->city;
            $newuser->headimgurl = $userinfo["default"]->headimgurl;
            $newuser->privilege  = $userinfo["default"]->privilege;
            $newuser->unionid    = $userinfo["default"]->unionid;

            $newuser->save();
        }

        return $next($request);
    }
}
