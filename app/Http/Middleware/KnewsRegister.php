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
        $original = $userinfo["default"]->original;
        $wxuser   = Wxuser::where("openid", "=", $original["openid"])->first();

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
            $newuser->fill       = 0;

            $newuser->save();
            var_dump($newuser->id);

        }else{
            var_dump("yes");
        }

        return $next($request);
    }
}
