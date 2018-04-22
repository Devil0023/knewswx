<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;

        $qinfo = Questionnaire::find($id);

        if($qinfo->id){
            echo $qinfo->title;
        }else{
            echo "问卷不存在";
        }
    }
}
