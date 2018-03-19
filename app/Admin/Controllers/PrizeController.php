<?php

namespace App\Admin\Controllers;

use App\Models\Prize;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PrizeController extends Controller
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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(Prize::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->prize("奖品名称");

            $grid->img("奖品图片")->display(function ($img){
                return "<img src=\"/uploads/{$img}\" style=\"width:20px;\"/>";
            });


            $grid->model()->orderBy('id', 'desc');
            $grid->paginate(30);
            $grid->disableExport();
            $grid->perPages([30, 40, 50]);

//            $grid->created_at();
//            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Prize::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('prize', "奖品名称");
            $form->image('img', "奖品图片")->removable();
            $form->datetimeRange("stime", "etime", "兑换时间");
            $form->number("num", "奖品数量");
            $form->number("cost", "兑换积分");
            $form->radio("checked", "发布")->options([0 => "否", 1 => "是"])->default(0);

            $form->textarea("intro", "奖品简介");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
