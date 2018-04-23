<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyresultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveyresult', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index()->comment('工号');
            $table->integer('qid')->index()->comment('问卷id');
            $table->mediumText('questionaire')->comment('问卷内容')->nullable();
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
        Schema::dropIfExists('surveyresult');
    }
}
