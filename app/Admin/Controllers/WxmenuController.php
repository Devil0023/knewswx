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

            $content->body(Wxmenu::tree());
        });
    }
    
}
