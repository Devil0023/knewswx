<?php

namespace App\Http\Controllers;

use App\Models\Questioninfo;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $questions = $this->getQuestions($questionnaire->id);

        return view("survey.index", compact("questionnaire","questions"));

    }

    public function submit(Request $request){

        $code = @session('knewsquestionnaire.code');

        var_dump($code);

        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $questions = $this->getQuestions($questionnaire->id);

        $count     = count($questions);

        $survey    = array();

        for($i = 1; $i <= $count; $i++){
            $var_name = "Question_".$i;
            //$survey[$questions[$i]["question"]] = $request->$var_name;

            var_dump($request->$var_name);

            var_dump($questions[$i]["question"]);
        }

        //return $survey;
    }

    public function check(Request $request){

        $result["error_code"]    = "0";
        $result["error_message"] = "success";

        $code = @$request->jobNumber;

        if(empty($code) || $this->checkCode($code) === false){
            $result["error_code"]    = "400001";
            $result["error_message"] = "工号不存在";
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
