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
        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id){
            header("HTTP/1.1 404 Not Found"); die;
        }

        $questions = $this->getQuestions($questionnaire->id);

        echo count($questions);
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
