<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');   /*默认长度为10不为负的整型*/
            $table->string('username')->unique(); /*唯一,默认不为空*/
            $table->string('email')->unique()->nullable(); /*邮箱可为空,但唯一*/
            $table->text('avatar_url')->nullable();   /*头像, 可存储在服务器 CDN 三方服务器*/
            //$table->string('country_code')->default('+86'); /*手机号地区*/
            $table->string('phone')->unique()->nullable();  /*考虑适用范围 国内外 +86*/
            $table->string('phone_captcha');    /*验证码*/
            $table->string('password',255); /*默认长度255*/
            $table->string('intro')->nullable();    /*简介可为空*/
            $table->timestamps();   /*创建时间(created_at)和更新时间(updated_at)*/
        });

        /*更灵活的方式存储图片--使用一张独立的image表存储图片链接:指定image类型 将类型指定为avatar(用户头像) user_id作为外键到这张表中即可,通过外键的方式将关系全部理清*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
