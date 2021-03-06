<?php

namespace App\Admin\Controllers;

use App\Models\Exchange;

use App\Models\Prize;
use App\Models\Wxuser;
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

    public function __construct(Request $request){
        $this->pid = $request->pid;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
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
            $grid->model()->orderBy("id", "desc");

            if($this->pid){
                $prize = Prize::find($this->pid);
                if($prize->id){
                    $grid->model()->where("pid", $this->pid);
                }
            }

            $grid->uid("用户昵称")->display(function ($uid){
                $wxuser = Wxuser::find($uid);
                return $wxuser->nickname;
            });


            $grid->created_at("创建时间");

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

            $form->display("wxuser.nickname", "昵称");
            $form->display("wxuser.sex", "性别")->with(function ($sex){
                switch($sex){
                    case 1; $value = "男"; break;
                    case 2: $value = "女"; break;
                    default: $value = "不明";
                }
                return $value;
            });

            $form->display("wxuser.language", "语言");
            $form->display("wxuser.province", "省份");
            $form->display("wxuser.city", "城市");
            $form->display("wxuser.country", "国家");
            $form->display("wxuser.headimgurl", "头像")->with(function ($img){
                return "<img src=\"$img\" style=\"width: 132px;\">";
            });

            $form->display("wxuser.mobile", "手机");
            $form->display("wxuser.address", "地址");

            $form->display('created_at', '创建时间');

            $form->disableSubmit();
            $form->disableReset();

            $form->tools(function (Form\Tools $tools){
                $tools->disableListButton();
            });
        });
    }
}
