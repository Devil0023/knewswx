<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Wxmenu extends Model
{
    use ModelTree, AdminBuilder;
    protected $table = 'wxmenu';
    protected $fillable = ["parent_id", "order", "title", "url"];

    public function __construct(array $attributes = []){

        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('order');
        $this->setTitleColumn('title');

    }
}
