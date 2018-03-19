<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Prize', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('奖品名称');
            $table->string('pic')->comment('奖品图片');
            $table->bigInteger('cost')->comment('兑换积分');
            $table->text('intro')->comment('奖品说明');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Prize');
    }
}
