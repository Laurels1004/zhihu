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
/*对Request::get方法进行封装*/
function rq($key=null, $default=null){
    /*如果调用函数rq()时没有指定实参,返回Request::all(),即返回所有传递来的参数(所有键值对)*/
    /*调用函数rq()时指定了实参(key),则通过Request::get()返回指定实参(key)的值(value)*/
    return !$key ? Request::all() : Request::get($key, $default);
}

/*实例化user对象*/
function user_ins(){
    return new App\Model\User;
}

/*实例化question对象*/
function question_ins(){
    return new App\Model\Question;
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


/*用户API*/
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


/*问题API*/
/*创建问题--注意:create与laravel中自带的方法名create重复*/
Route::any('question/add', function(){
    return question_ins()->add();
});

/*更新问题--注意:update与laravel中自带的方法名update重复*/
Route::any('question/change', function(){
    return question_ins()->change();
});

/*查看问题--注意:show与laravel中自带的方法名show重复*/
Route::any('question/read', function(){
    return question_ins()->read();
});

/*删除问题*/
Route::any('question/remove', function(){
    return question_ins()->remove();
});


/*测试接口*/
Route::any('test', function(){
//    dd(user_ins()->is_signup());
    dd(question_ins()->test());
});
