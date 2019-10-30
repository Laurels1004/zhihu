<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /*创建提问*/
    public function add(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');
        $this->user_id = session('uid');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查标题是否为空,空返回,不为空存入quesion中*/
        if(!rq('title'))
            return err('提问标题不能为空!');
        $this->title = rq('title');

        /*检查是否有提问描述,有存入question中,没有继续执行*/
        if(rq('desc'))
            $this->desc = rq('desc');

        /*数据入库*/
        return $this->save() ?
            suc(['id'=>$this->id ] ):
            err('提问创建失败!');
    }


    /*更新提问*/
    public function change(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*检查是否传递了提问的id*/
        if(!rq('id'))
            return err('需要更新的提问ID不能为空');

        /*检查提问是否存在*/
        $question = $this->find(rq('id'));
        if(!$question)
            return err('需要更新的提问不存在!');

        /*检查当前用户是否为提问发起者*/
        if($question->user_id != session('uid'))
            return err('非提问发起者不能对该提问进行修改!');

        /*是否修改了提问标题*/
        if(rq('title'))
            $question->title = rq('title');

        /*是否修改了提问描述*/
        !rq('desc') ? : $question->desc = rq('desc');
        //if(rq('desc'))
            //$question->desc = rq('desc');

        /*数据入库*/
        return $question->save() ?
            suc() :
            err('提问更新失败');
    }


    /*查看提问*/
    public function read(){
        /*检查是否含有对应id的提问,有返回对应id提问数据*/
        if(rq('id')){
            $question = $this->find(rq('id'));
            return $question ?
                suc(['data'=>$question]) :
                err('查看的提问不存在!');
        } else {
            /*不传递指定的提问id,返回查询到的所有提问*/
            /*$limit-设置一页显示数量*/
            //$limit = rq('limit')?:15;
            /*$skip-设置偏移量(偏移页数),偏移值为1,跳过$limit的数量*/
            //$skip = (rq('page')? rq('page') - 1: 0) * $limit;
            /* *
             * list()-将数组中的值传入list()中的变量中
             * 例如 list($a, ,$c) = array('a','b','c');
             * 就是将数组中的'a'赋值给变量$a,'c'赋值给变量中的$c
             * */
            list($limit, $skip) = pagenate(rq('page'), rq('limit'));

            /*
             * 构建query并返回collection数据,laravel会将其转换为json对象
             * orderBy('col1','col2',..., 'DESC')-通过指定字段来排序
             * limit()-显示数量
             * skip()-偏移量
             * get()-将所有查询条件转换成collection(与数组相似,是一个对象,具有强大的处理数组的功能)
             * get(['col1','col2'])-可指定取出的字段
             * keyBy()-以指定字段作为键,可将源get()所得的数组转换为json对象
             * */
            $questions = $this
                ->orderBy('created_at', 'ASC')
                ->limit($limit)
                ->skip($skip)
                ->get(['id', 'title', 'desc', 'user_id', 'created_at', 'updated_at'])
                ->keyBy('id');

            return suc(['data'=>$questions]);
        }
    }


    /*删除提问*/
    public function remove(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return err('用户未登录!');

        /*检查是否传递了提问的id*/
        if(!rq('id'))
            return err('需要删除的提问ID不能为空');

        /*检查提问是否存在*/
        $question = $this->find(rq('id'));
        if(!$question)
            return err('需要删除的提问不存在!');

        /*检查当前用户是否是提问发起者*/
        if(session('uid') != $question->user_id)
            return err('非提问发起者不能对该提问进行删除!');

        /*删除该提问下的所有回答*/
        answer_ins()->where('question_id', rq('id'))->delete();

        /*删除该提问下的所有评论*/
        comment_ins()->where('question_id', rq('id'))->delete();

        /*删除提问*/
        return $question->delete() ?
            suc() :
            err('提问删除失败!');
    }


    /*提问关联用户模型-一对多(一个用户可以提多个问题)*/
    public function user(){
        return $this->belongsTo('App\Model\User');
    }
}
