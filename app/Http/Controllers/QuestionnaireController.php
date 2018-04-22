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
        $data = @Redis::get($qkey);

        if(!is_array($data)){

            $questions = Questioninfo::where("qid", $questionnaire->id)
                ->where("deleted_at", null)
                ->orderBy("qorder", "asc")->toArray();

            var_dump($questions);
        }






    }
}
