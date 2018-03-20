<?php

namespace App\Admin\Controllers;

use App\Models\Pointsrule;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PointsruleController extends Controller
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

            $content->header('积分规则');
            $content->description('设置积分规则');

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

            $content->header('积分规则');
            $content->description('设置积分规则');

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

            $content->header('积分规则');
            $content->description('设置积分规则');

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
        return Admin::grid(Pointsrule::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->rule("规则");
            $grid->points("积分");
            $grid->intro("简介")->display(function($text) {
                return str_limit($text, 10, '...');
            });

            $grid->created_at();
            $grid->updated_at();

            $grid->model()->orderBy('id', 'desc');
            $grid->paginate(30);
            $grid->disableExport();
            $grid->perPages([30, 40, 50]);

            $grid->tools(function ($tools){
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->actions(function ($actions){
                $actions->disableDelete();
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Pointsrule::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("rule", "规则");
            $form->number("points", "积分");
            $form->textarea("intro", "简介");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
