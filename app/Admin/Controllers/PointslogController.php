<?php

namespace App\Admin\Controllers;

use App\Models\Pointslog;

use App\Models\Wxuser;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class PointslogController extends Controller
{
    use ModelForm;
    public $uid = 0;

    public function __construct(Request $request){
        $this->uid = $request->uid;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('积分日志');
            $content->description('查看积分日志详情');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

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

            $content->header('header');
            $content->description('description');

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


        return Admin::grid(Pointslog::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->points("积分");
            $grid->desc("说明");

            $grid->model()->orderBy("id", "desc");


            if($this->uid){
                $wxuser = Wxuser::find($this->uid);
                if($wxuser->id){
                    $grid->model()->where("uid", $wxuser->id);
                }
            }

            $grid->created_at("创建时间");

            $grid->disableExport();
            $grid->disableCreateButton();

            $grid->tools(function ($tools){
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->disableActions();

//            $grid->actions(function ($actions){
//                $actions->disableDelete();
//                $actions->disableEdit();
//            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Pointslog::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text("wxuser.id", "用户ID");

            $form->display('created_at', '创建时间');
        });
    }
}
