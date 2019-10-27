<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /*创建回答*/
    public function add(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查是否有回答内容与是否有对应提问的ID*/
        if(!(rq('content') && rq('question_id')))
            return err('提问ID和用户回答内容均不能为空!');

        /*检查传递上来的提问ID是否存在对应的提问*/
        $question = question_ins()->find(rq('question_id'));
        if(!$question)
            return err('提问不存在!');

        /*检查当前用户是否已经回答过了该问题*/
        /*where(['key'=>'value','key'=>'value'])-通过数组方式多条件查询*/
        $is_answered = $this
            ->where([
                'question_id'=>rq('question_id'),
                'user_id'=>session('uid')
              ])
            ->exists();
        if($is_answered)
            return err('用户不能在同一个提问下重复回答!');

        /*数据入库*/
        $this->user_id = session('uid');
        $this->content = rq('content');
        $this->question_id = $question->id;
        return $this->save() ?
            suc(['id'=>$this->id]) :
            err('回答创建失败!');
    }


    /*更新回答*/
    public function change(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查是否传递了回答ID*/
        if(!(rq('id') && rq('content')))
            return err('需要更新的回答ID和回答内容不能为空');

        /*检查回答是否存在*/
        $answer = $this->find(rq('id'));
        if(!$answer)
            return err('需要更新的回答不存在!');

        /*检查当前用户是否为回答发起者*/
        if($answer->user_id != session('uid'))
            return err('非回答发起者不能对该回答进行修改!');

        /*数据更新*/
        $answer->content = rq('content');
        return $answer->save() ?
            suc():
            err('回答更新失败!');
    }


    /*查看回答*/
    public function read(){
        /*检查是否传递了回答ID和提问ID*/
        //if(!(rq('id') || rq('question_id')))
        //if(!rq('id') && !rq('question_id'))
        if(!(rq('question_id') xor rq('id')))
            /*回答ID和提问ID同时不存在时执行*/
            return err('提问ID或回答ID不能为空,且只须存在一个!');

        /*检查是否传递了回答ID,有返回对应ID的回答数据*/
        if(rq('id')){
            /*查看某个回答*/
            $answer =$this->find(rq('id'));
            return $answer ?
                suc(['data'=>$answer]):
                err('查看的回答不存在');
        }

        /*检查提问是否存在*/
        if(!question_ins()->find(rq('question_id')))
            return err('查看的提问不存在!');

        /*查看一个问题下的所有回答*/
        $answers = $this
            ->where('question_id', rq('question_id'))
            ->get()
            ->keyBy('id');

        /*返回数据*/
        return !$answers->isEmpty() ?
            suc(['data'=>$answers]):
            err('该问题下还没有回答!');
    }


    /*删除回答*/
    public function remove(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*检查是否传递了回答的ID*/
        if(!rq('id'))
            return err('需要删除的回答ID不能为空!');

        /*检查回答是否存在*/
        $answer = $this->find(rq('id'));
        if(!$answer)
            return err('需要删除的回答不存在!');

        /*检查当前用户是否是回答发起者*/
        if(session('uid') != $answer->user_id)
            return err('非回答发起者不能对该回答进行删除');

        /*删除该回答下的所有评论*/
        comment_ins()->where('answer_id',rq('id'))->delete();

        /*删除回答*/
        return $answer->delete() ?
            suc() :
            err('回答删除失败!');
    }


    /*回答投票*/
    public function vote(){
        /*使用链接表中的新增关系*/
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*检查是否有回答ID和投票字段*/
        if(!(rq('id') && rq('vote')))
            return err('回答ID和投票字段不能为空!');

        /*检查要投票的回答是否存在*/
        $answer = $this->find(rq('id'));
        if(!$answer) return err('所投票的回答不存在!');

        /*对投票进行处理 1赞成,2反对*/
        $vote = rq('vote') <= 1 ? 1 : 2;

        /*检查用户是否已经投过票*/
        /*newPivotStatement()-进入中间表进行操作,也就是对链接表操作*/
        $answer->users()
            ->newPivotStatement()
            ->where([
                'user_id'=>session('uid'),
                'answer_id'=>rq('id')])
            ->delete();

        /*进行数据添加*/
        /**
         * 在之前$this->>find(rq('id'))时laravel就已经将rq('id')传入至实例的answer_id中了
         * $answer实例下有一个users()方法,这个方法返回一个关系,
         * 通过关系里的attach()方法,将数据传入链接表
        **/
        $answer
            ->users()
            ->attach(session('uid'), ['vote'=>$vote]);
        return suc();
    }


    /* *
     * 一个用户可给多个回答投票
     * 一个回答可被多个用户投票
     * answer : user => N : N 多对多关系
    * */
    /*回答关联用户模型*/
    public function users(){
        /*
         * belongsToMany-回答(Answer)属于多个用户(User)
         * 创建连接表(answers表与users表中间的那张表),将两张表的数据存放至一张表中
         * 创建表时一般为复数,连接表是个例外须是单数,表名排序按照字母表顺序排序
         * laravel不知道连接表的新增的字段,除了两个外键字段,如需添加其它字段,先进行声明注册
         * 使用withPivot()-进行新增字段声明
         * 使用withTimestamps-如果新增数据或修改数据,timestamps也会更新
         * */
        return $this
            ->belongsToMany('App\Model\User')
            ->withPivot('vote')
            ->withTimestamps();
    }
}
