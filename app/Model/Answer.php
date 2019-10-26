<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /*创建回答*/
    public function add(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return ['status'=>0, 'msg'=>'用户未登录!'];
        $this->user_id = session('uid');

        /*检查是否有回答内容与是否有对应提问的ID*/
        if(!(rq('content') && rq('question_id')))
            return ['status'=>0, 'msg'=>'提问ID和用户回答内容不能为空!'];
        $this->content = rq('content');

        /*检查传递上来的提问ID是否存在对应的提问*/
        $question = question_ins()->is_question_exists(rq('question_id'));
        if(!$question)
            return ['status'=>0, 'msg'=>'提问不存在!'];
        $this->question_id = $question->id;

        /*检查当前用户是否已经回答过了该问题*/
        /*where(['key'=>'value','key'=>'value'])-通过数组方式多条件查询*/
        $is_answered = $this
            ->where([
                'question_id'=>rq('question_id'),
                'user_id'=>session('uid')
              ])
            ->exists();
        if($is_answered)
            return ['status'=>0, 'msg'=>'一个提问下用户不能重复回答!'];

        /*数据入库*/
        return $this->save() ?
            ['status'=>1, 'id'=>$this->id] :
            ['status'=>0, 'msg'=>'回答创建失败!'];
    }

    /*更新回答*/
    public function change(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return ['status'=>0, 'msg'=>'用户未登录!'];

        /*检查是否传递了回答ID*/
        if(!(rq('id') && rq('content')))
            return ['status'=>0, 'msg'=>'回答ID和回答内容不能为空'];

        /*检查回答是否存在*/
        $answer = $this->find(rq('id'));
        if(!$answer)
            return ['status'=>0, 'msg'=>'回答不存在!'];

        /*检查当前用户是否为回答发起者*/
        if($answer->user_id != session('uid'))
            return ['status'=>0, 'msg'=>'非回答发起者不能对该回答进行修改!'];
        $answer->content = rq('content');

        /*数据更新*/
        return $answer->save() ?
            ['status'=>1]:
            ['status'=>0, 'msg'=>'回答更新失败!'];
    }

    /*查看回答*/
    public function read(){
        /*检查是否传递了回答ID和提问ID*/
        //if(!(rq('id') || rq('question_id')))
        if(!rq('id') && !rq('question_id'))
            /*回答ID和提问ID同时不存在时执行*/
            return ['status'=>0, 'msg'=>'回答ID或提问ID不能为空!'];

        /*检查是否传递了回答ID,有返回对应ID的回答数据*/
        if(rq('id')){
            /*查看某个回答*/
            $answer =$this->find(rq('id'));
            return $answer ?
                ['status'=>1, 'data'=>$answer]:
                ['status'=>0, 'msg'=>'回答不存在'];
        }

        /*检查提问是否存在*/
        if(!question_ins()->is_question_exists(rq('question_id')))
            return ['status'=>0, 'msg'=>'提问不存在!'];

        /*查看一个问题下的所有回答*/
        $answers = $this
            ->where('question_id', rq('question_id'))
            ->get()
            ->keyBy('id');

        /*返回数据*/
        return !$answers->isEmpty() ?
            ['status'=>1, 'data'=>$answers]:
            ['status'=>0, 'data'=>'该问题下还没有回答!'];
    }
}
