<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');   /*默认包含unsignedInterger与auto_increment*/
            $table->string('title', 64);
            $table->string('desc')->nullable()->comment('description'); /*comment注释*/
            $table->unsignedInteger('user_id'); /*unsignedInteger不为负,该字段是一个外键*/
            //$table->unsignedInteger('admin_id');
            $table->string('status')->default('ok');    /*文章状态 可见*/
            $table->timestamps();

            /*外键字段$table->foreign('当前表的外键字段')->references('另一张表中和该表外键字段对应的字段')->on('外键表名')*/
            $table->foreign('user_id')->references('id')->on('users');

            /*严谨的处理文章状态的做法:是新建一张表quesitons_status记录文章状态,通过$table->unsignedInteger('question_id');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
    }
}
