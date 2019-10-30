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
        /*Request::has('key')->检查请求数据中是否含有key,返回值:布尔类型,需use Request;*/
        //dd(Request::has('username'));
        /*Request::get('key') ->检查请求数据中是否含有keyname,返回值:value||null*/
        //dd(Request::get('username'));
        /*Request::all->获取所有请求数据,返回值:请求参数的数组;*/
        //dd(Request::all());

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查用户名和密码是否为空*/
        //if(!Request::get('username') && !Request::get('password'))
        $request_user_check = $this->has_username_and_password();
        if(!$request_user_check)
            return err('用户名和密码不可为空!');
        $username = $request_user_check[0];
        $password = $request_user_check[1];

        /*检查用户名是否存在*/
        if($this
            ->where('username', rq('username'))
            ->exists()
        )
            return err('用户名已存在!');

        /*加密密码--使用Hash::make()加密密码,需use Hash*/
        $hashed_password = Hash::make($password);

        /*数据入库*/
        $this->password = $hashed_password;
        $this->username = $username;
        /*if(!$this->save())
            return err('用户注册失败!');
        return suc(['id'=>$this->id]);*/
        return $this->save() ?
            suc(['id'=>$this->id]) :
            err('用户注册失败!');
    }


    /*登录API*/
    public function signin(){
        /*检查用户是否登录*/
        //if($this->is_signin())
        //return ['status'=>0, 'msg'=>'用户已登录!'];

        //dd(session('abc'));   /*获取键名为abc的值,没有返回null*/
        //dd(session('abc','bcd')); /*获取键名为abc的值,没有返回bcd*/

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查用户名和密码是否为空*/
        $request_user_check = $this->has_username_and_password();
        if(!$request_user_check)
            return err('用户名和密码不可为空!');
        $username = $request_user_check[0];
        $password = $request_user_check[1];

        /*检查数据库中是否存在该用户*/
        $user = $this->where('username', $username)->first();
        if(!$user)
            return err('用户不存在!');

        /*检查用户密码是否正确,使用Hash::check(strintg1,string2)方法*/
        $hashed_password = $user->password;
        if(!Hash::check($password, $hashed_password))
            return err('用户密码错误!');

        /*laravel 默认session值:_token;_previous=>[url=>'']*/
        /*session()->put('key', 'value')--在session中将value存入key*/
        /*将用户信息写入session*/
        session()->put('username', $user->username);
        session()->put('uid', $user->id);

        return suc(['id'=>$user->id]);
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
        return redirect('/');

        /*单页面返回JSON状态码*/
//        return suc();
    }


    /*用户个人信息API-游客视野*/
    public function info(){
        if(!rq('id'))
            return err('用户ID不能为空!');

        /*设置可见字段*/
        $get = ['id','username','avatar_url','intro'];

        /*find(param1,param2)-param1:id;param2:指定取出的字段，与get([,,,])类似*/
        $user = $this->find(rq('id'), $get);
        if(!$user)
            return err('用户不存在!');

        //$data = $user->toArray();

        $answer_count = $user->answers()->count();
        //$answers_count = answer_ins()->where('user_id', rq('id'))->count();
        /* *
         * 需要创建链接表
         * $question_count = $user->question()->count();
         * */
        $questions_count = question_ins()->where('user_id', rq('id'))->count();
        $data['answer_count'] = $answer_count;
        $data['question_count'] = $questions_count;

        return suc($data);

    }


    /*密码修改API*/
    public function change_password(){
        /*检查用户是否登录*/
        if(!$this->is_signin())
            return err('用户未登录!');

        /*检查是否传递了参数*/
        if(!(rq('old_pass') && rq('password')))
            return err('旧密码与新密码不能为空!');

        /*检查旧密码是否正确*/
        $user = $this->find(session('uid'));
        if(!Hash::check(rq('old_pass'), $user->password))
            return err('旧密码输入错误!');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*加密数据*/
        $user->password = bcrypt(rq('password'));
        return $user->save() ?
            suc() :
            err('密码保存失败!');
    }


    /*用户密码重置*/
    public function reset_password(){
        /* *
         * 通过手机找回密码,通过邮箱找回密码
         * 注意预防用户恶意短信轰炸,接口调用,例如每秒调用100次!
         * 在session中存一些信息,通过这些信息判断最近发送短信是在什么时间
         * 间隔过于短,过于频繁则放置该用户到黑名单中
         * */
        /*检查是否是机器人*/
        if($this->is_robot(60))
            return err('操作过于频繁!');

        /*检查是否传递参数*/
        if(!rq('phone'))
            return err('手机号不能为空!');

        /*检查数据库中是否有这个phone的信息*/
        $user = $$this->where('phone', rq('phone'))->first();
        $phone_exist = $user->exists();
        if(!$phone_exist)
            return err('该手机号未注册!');

        /*三方接口接入进行验证*/
        /*生成验证码*/
        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;
        if($user->save()){
            /*发送短信*/
            $this->send_sms();
            $this->update_robot_time();
            return suc();
        }
        return err('数据更新失败!');
    }


    /*密码重置验证*/
    public function validate_reset_password(){
        /*检查是否是机器人*/
        if($this->is_robot(2))
            return err('操作过于频繁!');

        /*检查参数中是否有phone字段*/
        if(!(rq('phone') && rq('phone_captcha') && rq('password')))
            return err('用户手机号、验证码和密码不能为空!');

        /*验证数据库中是否有相关信息*/
        $user = $this->where([
            'phone'=>rq('phone'),
            'phone_captcha'=>rq('phone_captcha')
            ])->first();
        if (!$user)
            return err('手机号或验证码错误!');

        /*bcrypt()-加密*/
        $user->password = bcrypt(rq('password'));
        $this->update_robot_time();
        return !$user->save() ?
            err('密码修改失败!') :
            suc();
    }


    /*判断是否是机器操作*/
    public function is_robot($time = 20){
        /*session中没有记录，则接口未被调用过*/
        if(!session('last_action_time'))
            return false;

        /*间隔时间*/
        $elapsed = time() - session('last_action_time');
        /*当前时间-最后一次发送和短信的时间<20*/
        return ($elapsed < $time);
    }


    /*更新机器验证时间*/
    public function update_robot_time(){
        session()->put('last_action_time', time());
    }


    /*发送短信*/
    public function send_sms(){
        return true;
    }


    /*生成验证码*/
    public function generate_captcha(){
        return rand(1000, 9999);
    }


    /*检查请求的用户名和密码是否为空*/
    public function has_username_and_password(){
        $username = rq('username');
        $password = rq('password');

        /*用户名或密码为空,返回false,否则返回含有用户名密码的数组*/
        return $username && $password ?
            [$username,$password] :
            false;
    }


    /*检查用户是否登录*/
    public function is_signin(){
        return is_signin();
    }


    /*检查用户是否存在*/
    /*public function is_user_exists($username){
        $user = $this->where('username', $username)->first();
        return $user ? : false;
    }*/


    /*检查用户名是否存在*/
    public function is_username_exist(){
        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查是否传递了用户名*/
        //if(!rq('username'))
        //    return err('用户名不能为空!');

        /*返回值类型-布尔型*/
        //$user = $this
        //    ->where('username', rq('username'))
        //   ->exists();

        //return $user ?
        //  suc(['msg'=>'用户名已存在!']):
        //  err('用户名不存在!');
        return suc(['count'=>$this->where(rq())->count()]);
    }


    /*用户关联回答模型*/
    public function answers(){
        return $this
            ->belongsToMany('App\Model\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }


    /*用户关联问题模型*/
    public function questions(){
        return $this
            ->belongsToMany('App\Model\Question')
            ->withPivot('vote')
            ->withTimestamps();
    }

}
