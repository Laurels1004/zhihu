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
    return view('index');
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

/**
 * 分页功能封装
 * param1:$page-页码
 * param2:$limit-每页显示数量
 * return:array($limit, $skip);$skip-偏移量
 **/
function pagenate($page=1, $limit=16){
    $limit = $limit ?: 16;
    /*$skip-设置偏移量(偏移页数),偏移值为1,跳过$limit的数量*/
    $skip = ($page? $page - 1: 0) * $limit;
    return [$limit, $skip];
}

/*返回值快捷函数封装*/
function err($msg=null){
    return ['status'=>0, 'msg'=>$msg];
}

function suc($data_to_merge = []){
    /*设置status状态,data默认为空*/
    $data = ['status'=>1, 'data'=>[]];
    if($data_to_merge)
        /*如果参数中有数据,则使用array_merge合并,(这个数据是一个数组形式,如['data'=>$data])*/
        $data['data'] = array_merge($data['data'], $data_to_merge);
    /*参数中没有数据时,则直接返回['status'=>1, 'data'=>[]]*/
    return $data;
}

/*实例化user对象*/
function user_ins(){
    return new App\Model\User;
}

/*实例化question对象*/
function question_ins(){
    return new App\Model\Question();
}

/*实例化answer对象*/
function answer_ins(){
    return new App\Model\Answer();
}

/*实例化comment对象*/
function comment_ins(){
    return new App\Model\Comment();
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
    return ['version'=>'0.0.6','developer'=>'laurels1004'];
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

/*用户个人信息-游客视角*/
Route::any('user/info', function(){
    return user_ins()->info();
});

/*用户密码修改*/
Route::any('user/change_password', function(){
    return user_ins()->change_password();
});

/*用户密码重置*/
Route::any('user/reset_password', function(){
    return user_ins()->reset_password();
});

/*用户密码重置验证*/
Route::any('user/validate_reset_password', function(){
    return user_ins()->validate_reset_password();
});


/*判断用户名是否存在*/
Route::any('user/is_uname_exist', function(){
    return user_ins()->is_username_exist();
});


/*提问API*/
/*创建提问--注意:create与laravel中自带的方法名create重复*/
Route::any('question/add', function(){
    return question_ins()->add();
});

/*更新提问--注意:update与laravel中自带的方法名update重复*/
Route::any('question/change', function(){
    return question_ins()->change();
});

/*查看提问--注意:show与laravel中自带的方法名show重复*/
Route::any('question/read', function(){
    return question_ins()->read();
});

/*删除提问*/
Route::any('question/remove', function(){
    return question_ins()->remove();
});


/*回答API*/
/*创建回答*/
Route::any('answer/add', function(){
    return answer_ins()->add();
});

/*更新回答*/
Route::any('answer/change', function(){
    return answer_ins()->change();
});

/*查看回答*/
Route::any('answer/read', function(){
    return answer_ins()->read();
});

/*删除回答*/
Route::any('answer/remove', function(){
    return answer_ins()->remove();
});

/*投票回答*/
Route::any('answer/vote', function(){
    return answer_ins()->vote();
});

/*评论API*/
/*创建评论*/
Route::any('comment/add', function(){
    return comment_ins()->add();
});

/*更新评论*/


/*查看评论*/
Route::any('comment/read', function(){
    return comment_ins()->read();
});

/*删除评论*/
Route::any('comment/remove', function(){
    return comment_ins()->remove();
});


/*通用API*/
/*时间线*/
Route::any('timeline', 'CommonController@timeline');



/*测试接口*/
Route::any('test', function(){
//    dd(user_ins()->is_signin());
//    dd(question_ins()->test());
//    $a = false;
//    $b = 1;
//    if(!$a && !$b){
//        dd(1);
//    } else {
//        dd(!$b);
//    }

//    dd(user_ins()->is_username_exists('test123'));
    $a = ['data'=>'123'];
    $b = ['status'=> '1'];
    dd(array_merge($a,$b));
});
