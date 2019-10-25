<?php

namespace App\Model;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /*注册API*/
    public function signup(){
        /*dd() -> dump&die*/
        /*Request::has('key')->判断请求数据中是否含有key,返回值:布尔类型,需use Request;*/
        //dd(Request::has('username'));
        /*Request::get('key') ->判断请求数据中是否含有keyname,返回值:value||null*/
        //dd(Request::get('username'));
        /*Request::all->获取所有请求数据,返回值:请求参数的数组;*/
        //dd(Request::all());

        /*检查用户名和密码是否为空*/
        //if(!Request::get('username') && !Request::get('password'))
        $request_user_check = $this->has_username_and_passowrd();
        if(!$request_user_check)
            return ['status'=>0, 'msg'=>'用户名和密码不可为空!'];
        $username = $request_user_check[0];
        $password = $request_user_check[1];

        /*检查用户名是否存在*/
        $user = $this
            ->where('username', $username)
            ->exists();

        if($user)
            return ['status'=>0, 'msg'=>'用户名已存在!'];

        /*加密密码--使用Hash::make()加密密码,需use Hash*/
        $hashed_password = Hash::make($password);

        /*存入数据库*/
        $this->password = $hashed_password;
        $this->username = $username;
        if($this->save())
            return ['status'=>1, 'id'=>$this->id];
        else
            return ['status'=>0, 'msg'=>'用户注册失败!'];
    }

    /*登录API*/
    public function signin(){
        /*判断用户是否登录*/
        if($this->is_signin())
            return ['status'=>0 ,'msg'=>'用户已登录!'];

        //dd(session('abc'));   /*获取键名为abc的值,没有返回null*/
        //dd(session('abc','bcd')); /*获取键名为abc的值,没有返回bcd*/
        /*检查用户名和密码是否为空*/
        $request_user_check = $this->has_username_and_passowrd();
        if(!$request_user_check)
            return ['status'=>0, 'msg'=>'用户名和密码不可为空!'];
        $username = $request_user_check[0];
        $password = $request_user_check[1];

        /*检查数据库中是否存在该用户*/
        $user = $this->where('username', $username)->first();
        if(!$user)
            return ['status'=>0, 'msg'=>'用户不存在!'];

        /*检查用户密码是否正确,使用Hash::check(strintg1,string2)方法*/
        $hashed_password = $user->password;
        if(!Hash::check($password, $hashed_password))
            return ['status'=>0, 'msg'=>'用户密码错误!'];

        /*laravel 默认session值:_token;_previous=>[url=>'']*/
        /*session()->put('key', 'value')--在session中将value存入key*/
        /*将用户信息写入session*/
        session()->put('username', $user->username);
        session()->put('uid', $user->id);

        return ['status'=>1, 'id'=>$user->id];
    }


    /*登出API*/
    public function logout(){
        /*session的嵌套*/
        //session()->set('person.name', 'xiaoming');
        //session()->set('person.friend.hanmeimei.age',  '20');

        /*session()->pull()--取值后删除键值对在session中的信息,也相当于剪切*/
        //$username = session()->pull('username');
        //$uid = session()->pull('uid');

        /*session()->put()--通过赋值方式,赋值为null,键名保留*/
        //session()->put('username', null);
        //session()->put('uid', null);

        /*session()->flush()--清除所有session信息,请求重新开始便重新创建新的session*/
        //session()->flush();

        /*session()->forget()--删除session中的键值对*/
        session()->forget('usernamme');
        session()->forget('uid');

        /*普通网页应用进行页面跳转*/
        //return redirect('/');

        /*单页面返回JSON状态码*/
        return ['status'=>1];
    }

    /*检查请求的用户名和密码是否为空*/
    public function has_username_and_passowrd(){
        $username = Request::get('username');
        $password = Request::get('password');

        /*用户名或密码为空,返回false,否则返回含有用户名密码的数组*/
        if($username && $password)
            return [$username,$password];
        return false;
    }

    /*检查用户是否登录*/
    public function is_signin(){
        /*session中存在uid,返回uid,否则返回false*/
        return session('uid')?: false;
    }
}
