<?php

namespace App\Admin\Controllers;

use App\Models\Wxuser;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WxuserController extends Controller
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

            $content->header('微信用户');
            $content->description('查看微信用户信息');

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

            $content->header('微信用户');
            $content->description('查看微信用户信息');

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

            $content->header('微信用户');
            $content->description('查看微信用户信息');

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
        return Admin::grid(Wxuser::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->nickname("昵称");
            $grid->headimgurl("头像")->display(function ($img){
                return "<img src=\"$img\" style=\"width:25px;\">";
            });

            $grid->column("fill", "完善资料")->display(function ($fill){
                return ($fill)? "是": "否";
            });

            $grid->created_at();
            $grid->updated_at();

            $grid->disableCreateButton();
            $grid->disableExport();

            $grid->model()->orderBy("id", "desc");

            $grid->tools(function ($tools){
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->actions(function ($actions){
                $actions->disableDelete();
                $actions->append('<a href="'.url("admin/points/log?uid=".$actions->getKey()).'"><i class="fa fa-eye"></i></a>');
            });

            $grid->filter(function ($filter){
                $filter->like("nickname", "昵称");
            });

            $grid->perPages([30, 40, 50]);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Wxuser::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display("nickname", "昵称");
            $form->display("sex", "性别")->with(function ($sex){
                switch($sex){
                    case 1; $value = "男"; break;
                    case 2: $value = "女"; break;
                    default: $value = "不明";
                }
                return $value;
            });

            $form->display("language", "语言");
            $form->display("province", "省份");
            $form->display("city", "城市");
            $form->display("country", "国家");
            $form->display("headimgurl", "头像")->with(function ($img){
                return "<img src=\".$img.\" style=\"width: 100px;\">";
            });

            $form->display("mobile", "手机");
            $form->display("address", "地址");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->disableSubmit();
            $form->disableReset();

        });
    }
}
