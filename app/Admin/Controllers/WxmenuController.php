<?php

namespace App\Admin\Controllers;

use App\Models\Wxmenu;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Widgets\Box;



class WxmenuController extends Controller
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

            $content->header('Wxmenu');
            $content->description('description');

            $content->row(function (Row $row){
                $row->column(12, function(Column $column){
                    $column->row('111');

                });
            });

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_base_path('wxmenu'));

                    $form->select('parent_id', "父节点")->options(Wxmenu::selectOptions());
                    $form->text('title', "标题")->rules('required');
                    $form->text('url', "地址");

                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box("创建新菜单", $form))->style('success'));
                });
            });

//            $content->body(Wxmenu::tree(function ($tree){
//                $tree->branch(function ($branch){
//                    return $branch["id"]."--".$branch["title"]."--".$branch["url"];
//
//
//                });
//            }));
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
        return Admin::grid(Wxmenu::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->title("标题");
            $grid->url("地址");

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
        return Admin::form(Wxmenu::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text("title", "标题");
            $form->text("url", "地址");
            $form->select('parent_id', "父节点")->options(Wxmenu::selectOptions());

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    protected function treeView(){
        return Wxmenu::tree(function (Tree $tree){

            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "<strong>{$branch['title']}</strong>";

                if (!isset($branch['children'])) {
                    $uri = $branch["url"];
                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
                }

                return $payload;
            });

        });
    }
}
