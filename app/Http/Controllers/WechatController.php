<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function user(){

        //$userinfo = session("wechat.oauth_user");
        //var_dump($userinfo);



        //dd(session("wechat.oauth_user"));

        dd(session("wxuser"));

        die;

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });



        return $app->server->serve();

    }
}
