<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->index()->comment('用户id');
            $table->string('openid')->index()->comment('用户openid');
            $table->string('method')->default('add')->comment('操作方法');
            $table->integer('points')->comment('操作分值');
            $table->text('desc')->comment('说明')->nullable();
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
        Schema::dropIfExists('points_log');
    }
}
