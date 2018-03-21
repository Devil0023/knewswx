<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function prizelist(){
        $list = Prize::where("checked", 1)->all();
        var_dump($list);
    }

    public function prize($id){
        echo $id;
    }
}
