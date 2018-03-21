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

        print_r($userinfo["default"]); die;

        $wxuser   = Wxuser::where("openid", "=", $userinfo->openid);

        echo $userinfo;
        echo $wxuser->id;
        die;

        if($wxuser->id){
            var_dump(1);
        }else{
            $newuser = new Wxuser();
            $newuser->openid = $userinfo->openid;
            $newuser->nickname = $userinfo->nickname;
            $newuser->sex = $userinfo->sex;
            $newuser->province = $userinfo->province;
            $newuser->city = $userinfo->city;

        }

        return $next($request);
    }
}
