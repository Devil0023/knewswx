<?php

namespace App\Admin\Controllers;

use App\Models\Exchange;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    use ModelForm;
    public $pid = 0;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Request $request)
    {
        $this->pid = $request->pid;

        return Admin::content(function (Content $content) {

            $content->header('奖品兑换');
            $content->description('兑换列表');

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

            $content->header('奖品兑换');
            $content->description('兑换列表');

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

            $content->header('奖品兑换');
            $content->description('兑换列表');

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
        return Admin::grid(Exchange::class, function (Grid $grid) {

            $grid->id('ID')->sortable();


            $grid->created_at();
            $grid->updated_at();

            $grid->disableCreateButton();
            $grid->disableExport();

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
        return Admin::form(Exchange::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}