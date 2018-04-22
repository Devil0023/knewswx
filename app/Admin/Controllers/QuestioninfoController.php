<?php

namespace App\Admin\Controllers;

use App\Models\Questioninfo;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class QuestioninfoController extends Controller
{
    use ModelForm;
    public $qid = 0;

    public function __construct(Request $request){
        $this->qid = $request->qid;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('问卷设计');
            $content->description('问卷设计');

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
        $id  = $request->questioninfo;

        return Admin::content(function (Content $content) use ($id) {

            $content->header('问卷设计');
            $content->description('问题编辑');

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

            $content->header('问卷设计');
            $content->description('问题创建');

            $content->body($this->form());
        });
    }

    public function update(Request $request){

        $id = $request->questioninfo;
        echo "update:".$id;

    }

    public function destroy(Request $request){
        $id    = $request->questioninfo;
        $qinfo = Questioninfo::find($id);

        $result["status"]      = false;
        $result["message"]     = "Delete failed!";
        if($qinfo->delete()){
            $result["status"]  = true;
            $result["message"] = "Delete succeeded !";
        }

        return json_encode($result);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Questioninfo::class, function (Grid $grid) {

            $grid->model()->where("qid", $this->qid);
            $grid->model()->orderBy("qorder", "asc");

            $grid->id("ID")->sortable();

            $grid->qorder('题目顺序')->sortable();
            ;
            $grid->question('问题');

            $grid->type("题型")->display(function ($type){
                $string = "单选";
                switch($type){
                    case 0: $string = "单选"; break;
                    case 1: $string = "多选"; break;
                    case 2: $string = "开放"; break;
                }

                return $string;
            });

            $grid->isrequired("必填")->display(function ($isrequired){
                return $isrequired? "是": "否";
            });



            $grid->created_at();
            $grid->updated_at();



        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Questioninfo::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("question", "问题");
            $form->radio("type", "类型")->options([0 => "单选", 1 => "多选", 2 => "开放"])->default(0);
            $form->radio("isrequired", "必填")->options([0 => "否", 1 => "是"])->default(0);
            $form->textarea("options", "选项(换行区分，'其他'为else)");
            $form->select("qorder", "题目顺序")->options([
                1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5,
                6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10,
                11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15,
                16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20,
                21 => 21, 22 => 22, 23 => 23, 24 => 24, 25 => 25,
                26 => 26, 27 => 27, 28 => 28, 29 => 29, 30 => 30,
                31 => 31, 32 => 32, 33 => 33, 34 => 34, 35 => 35,
                36 => 36, 37 => 37, 38 => 38, 39 => 39, 40 => 40,
            ])->default(1);

            $form->hidden("qid")->value($this->qid);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
