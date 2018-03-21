<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{
    //

    public function __construct(){
        //$userinfo = session('wechat.oauth_user');
        //var_dump($userinfo);
        echo 2;
    }

    public function user(){

        echo 1;

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });



        return $app->server->serve();

    }
}
