<?php

namespace App\Http\Controllers;

use App\Models\Questioninfo;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $questionnaire = Questionnaire::find($request->id);
        if(!@$questionnaire->id){
            header("HTTP/1.1 404 Not Found"); die;
        }

        echo $questionnaire->id;

        $questions = Questioninfo::where("qid", $questionnaire->id)
            ->where("deleted_at", null)
            ->orderBy("qorder", "asc");

        foreach($questions as $key => $val){
            var_dump($val->question);
        }

        
    }
}
