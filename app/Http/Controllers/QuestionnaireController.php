<?php

namespace App\Http\Controllers;

use App\Models\Questioninfo;
use App\Models\Questionnaire;
use App\Models\Surveyresult;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {

        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $do     = "0";
        $cookie = $request->cookie("KnewsQuestionnaire-".$questionnaire->id);

        if(is_null($cookie)){
            $do = "1";
        }

        $questions = $this->getQuestions($questionnaire->id);

        return view("survey.index", compact("questionnaire","questions", "do"));

    }

    public function submit(Request $request){

        $code = @session('knewsquestionnaire.code');

        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $questions = $this->getQuestions($questionnaire->id);

        $survey    = array();

        foreach($questions as $val){
            $var_name = "Question_".$val["qorder"];
            $survey[$val["qorder"].".".$val["question"]] = is_null(@$request->$var_name)? "": @$request->$var_name;
        }

        $surveyresult = Surveyresult::create(array(
            "code" => $code,
            "qid"  => $questionnaire->id,
            "questionaire" => json_encode($survey),
        ));

        if($surveyresult){
            $result["error_code"]    = "0";
            $result["error_message"] = "success";

            Cookie::queue('KnewsQuestionnaire-'.$questionnaire->id, md5(time()), 1);
        }else{
            $result["error_code"]    = "400002";
            $result["error_message"] = "保存失败！";
        }

        return $result;
    }

    public function check(Request $request){

        $result["error_code"]    = "0";
        $result["error_message"] = "success";

        $code = @$request->jobNumber;

        if(empty($code) || $this->checkCode($code) === false){
            $result["error_code"]    = "400001";
            $result["error_message"] = "工号不存在！";
        }else{
            session(['knewsquestionnaire.code' => $code]);
        }

        return $result;
    }

    private function checkCode($code){

        $list = array("01990120");

        return in_array($code, $list);
    }

    private function getQuestions($qid){
        $qkey = "Questionnaire-".$qid;
        $json = @Redis::get($qkey);

        if(empty($json)){

            $json = Questioninfo::where("qid", $qid)
                ->where("deleted_at", null)
                ->orderBy("qorder", "asc")->get()->toJson();

            @Redis::setex($qkey, 300, $json);
        }

        return json_decode($json, true);
    }
}
