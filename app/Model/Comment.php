<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /*创建评论*/
    public function add(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查是否有评论内容*/
        if(!rq('content'))
            return err('评论内容不能为空!');

        /*检查是否有提问ID或回答ID,且二者择其一,异或XOR*/

        //if(
        //    (!rq('question_id') && !rq('answer_id')) ||
        //    (rq('question_id') && rq('answer_id'))
        //)
        if(!(rq('question_id') xor rq('answer_id')))
            return err('提问ID或回答ID不能为空,且只须存在一个!');

        /*如果评论的是问题*/
        if(rq('question_id')){
            /*检查提问ID对应的提问是否存在*/
            $question = question_ins()->find(rq('question_id'));
            if(!$question) return err('要评论的提问不存在!');
            $this->question_id = rq('quesiton_id');
        } else {
            /*如果评论的是回答*/
            /*检查回答ID对应的回答是否存在*/
            $answer = answer_ins()->find(rq('answer_id'));
            if(!$answer) return err('要评论的回答不存在!');
            $this->answer_id = rq('answer_id');
        }

        /*检查是否回复评论*/
        if(rq('reply_to'))
        {
            /*检查目标评论是否存在*/
            $target = $this->find(rq('reply_to'));
            if(!$target)
                return err('要回复的评论不存在!');
            //if($target->user_id == session('uid'))
            //    return err('用户不可自己评论自己!');
            $this->reply_to = rq('reply_to');
        }

        /*数据入库*/
        $this->user_id = session('uid');
        $this->content = rq('content');
        return $this->save() ?
            suc(['id'=>$this->id]) :
            err('评论创建失败!');
    }


    /*查看评论*/
    public function read(){
        /*检查是否提问ID或回答ID*/
        if(!(rq('question_id') xor rq('answer_id')))
            return err('提问ID或回答ID不能为空,且只须存在一个!');

        if(rq('question_id')){
            /*对于查看问题的评论的处理*/
            $question = question_ins()->find(rq('question_id'));
            if(!$question) return err('查看评论对应的提问不存在');
            $data = $this->where('question_id', rq('question_id'));
        } else {
            /*对于查看回答的评论的处理*/
            $answer = answer_ins()->find(rq('answer_id'));
            if(!$answer) return err('查看评论对应的回答不存在');
            $data = $this->where('answer_id', rq('answer_id'));
        }

        /*数据格式化,对查询构造器进行处理*/
        $data = $data->get()->keyBy('id');
        return !$data->isEmpty() ?
            suc(['data'=>$data]) :
            err('该模块下还没有评论!');
    }


    /*删除评论*/
    public function remove(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*检查是否传递了评论的ID*/
        if(!rq('id'))
            return err('要删除的评论ID不能为空');

        /*检查对应的评论是否存在*/
        $comment = $this->find(rq('id'));
        if(!$comment)
            return err('要删除的评论不存在!');

        /*检查当前用户是否是评论发起人*/
        if($comment->user_id != session('uid'))
            return err('非评论发起者不能对该评论进行删除!');

        /*删除该评论下的所有回复*/
        $this->where('reply_to', rq('id'))->delete();

        /*删除评论*/
        return $comment->delete()?
            suc():
            err('评论删除失败!');
    }
}

