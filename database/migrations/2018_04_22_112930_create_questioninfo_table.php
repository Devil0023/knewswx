<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestioninfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questioninfo', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question')->comment('问题');
            $table->integer('type')->comment('类型 0 单选 1 多选 2 开放');
            $table->tinyInteger('isrequired')->comment('必填');
            $table->text('options')->comment('选项');
            $table->integer('qorder')->comment('题目顺序');
            $table->integer('qid')->index()->comment('问卷id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questioninfo');
    }
}
