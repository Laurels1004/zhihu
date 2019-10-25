<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
/* *
 * 如果帮助函数过多,可在app/目录下新建一个help或funciton目录存放帮助函数文件,
 * 之后在app/Providers/AppServiceProvider.php文件下的register方法中进行注册
 * 之后的每次程序运行都会进行注册,routes Model Controller都可用
 * */
/*实例化对象*/
function user_ins(){
    /*实例化Model下的User*/
    return new App\Model\User;
}

/*api:面向客户端的接口,接口不限制客户端访问方式,可为get可为post
    Route::any('api'function(){}); --不限制客户端访问方式
*/

/*API版本号*/
Route::any('api',function(){
    /*返回以一个字符串*/
    //return 'abc';
    /*浏览器Content-Type: text/html; charset=UTF-8*/

    /*返回一个数组,laravel默认会将数组转为JSON*/
    return ['version'=>'0.0.1','developer'=>'laurels1004'];
    /*浏览器Content-Type: application/json*/
});

/*用户注册*/
Route::any('user/signup', function(){
    return user_ins()->signup();
});

/*用户登录*/
Route::any('user/signin', function(){
    return user_ins()->signin();
});

/*用户登出*/
Route::any('user/logout', function(){
    return user_ins()->logout();
});

/*测试接口*/
Route::any('test', function(){
    dd(user_ins()->is_signin());
});
