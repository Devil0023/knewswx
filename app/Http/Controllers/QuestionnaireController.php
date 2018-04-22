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

        $qkey = "Questionnaire-".$questionnaire->id;
        $json = @Redis::get($qkey);

        if(empty($json)){

            $json = Questioninfo::where("qid", $questionnaire->id)
                ->where("deleted_at", null)
                ->orderBy("qorder", "asc")->get()->toJson();

            @Redis::setex($qkey, 300, $json);
        }

        $questions = json_decode($json, true);

        return view("survey.index", compact("questionnaire","questions"));

    }
}
