<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAnswerUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('answer_id');
            $table->smallInteger('vote');   /*只有0->未投票 1->赞成 2->反对*/
            $table->timestamps();

            /*添加外键*/
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('answer_id')->references('id')->on('answers');
            /*
             * 设置唯一键,一个用户对一个回答只能投一次票,要么赞成要么反对要么不投,三个字段只能出现一次
             * unique()-可传一个数组
             * */
            $table->unique(['user_id','answer_id','vote']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answer_user');
    }
}
