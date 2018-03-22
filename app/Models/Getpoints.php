<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Getpoints extends Model
{
    //
    static public function getPointsByRule($uid, $ruleid){

        //用户不存在
        $user = Wxuser::find($uid);
        if(is_null($user)){
            return false;
        }

        //规则不存在
        $rule = Pointsrule::find($ruleid);
        if(is_null($rule)){
            return false;
        }

        DB::beginTransaction();
        try{

            if($user->points + $rule->points <= 0){  //该操作使积分小于0的时候  将积分置为0

                $user->update(array(
                    "points" => 0
                ));

                Pointslog::create(array(
                    "uid"    => $user->id,
                    "openid" => $user->openid,
                    "points" => (0 - $user->points),
                    "desc"   => "减分至0",
                ));

            }else{

                $user->increment("points", $rule->points);

                Pointslog::create(array(
                    "uid"    => $user->id,
                    "openid" => $user->openid,
                    "points" => $rule->points,
                    "desc"   => $rule->intro,
                ));

            }

            DB::commit();
            return true;

        }catch(Exception $e){
            DB::rollBack();
            return false;
        }


    }

    static public function getPoints($uid, $points, $desc){
        //用户不存在
        $user = Wxuser::find($uid);
        if(is_null($user)){
            return false;
        }

        //积分不够扣除
        if($uid->points + $points < 0){
            return false;
        }

        DB::beginTransaction();
        try{

            $user->increment("points", $points);

            Pointslog::create(array(
                "uid"    => $user->id,
                "openid" => $user->openid,
                "points" => $points,
                "desc"   => $desc,
            ));

            DB::commit();
            return true;

        }catch(Exception $e){

            DB::rollBack();
            return false;
        }

    }
}
