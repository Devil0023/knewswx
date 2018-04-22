<?php

namespace App\Admin\Controllers;

use App\Models\Questionnaire;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class QuestionnaireController extends Controller
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
        return Admin::grid(Questionnaire::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->title("问卷名称");
            $grid->intro("问卷简介");
            $grid->checked("发布")->display(function ($checked) {
                return $checked ? '是' : '否';
            });

            $grid->model()->orderBy('id', 'desc');

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
        return Admin::form(Questionnaire::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text("title", "问卷名称");
            $form->text("intro", "问卷简介");
            $form->radio("checked", "发布")->options([0 => "否", 1 => "是"])->default(0);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
