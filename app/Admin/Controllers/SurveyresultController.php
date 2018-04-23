<?php

namespace App\Admin\Controllers;

use App\Models\Surveyresult;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class SurveyresultController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('调查结果');
            $content->description('列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    //public function edit($id)
    public function edit(Request $request)
    {
        $id = $request->surveyresult;

        return Admin::content(function (Content $content) use ($id) {

            $content->header('调查结果');
            $content->description('列表');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('调查结果');
            $content->description('列表');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Surveyresult::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();

            $grid->disableCreateButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Surveyresult::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display("questionaire", "问卷信息")->with(function ($json){

                $data   = json_decode($json);
                $survey = "";

                foreach($data as $key => $val){
                    $survey .= $key."<br/>";

                    if(is_array($val)){
                        $survey .= implode("、", $val)."<br/><br/>";
                    }else{
                        $survey .= $val."<br/><br/>";
                    }

                }

                return $survey;

            });

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->disableSubmit();
            $form->disableReset();

        });
    }
}
