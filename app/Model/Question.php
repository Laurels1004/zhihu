<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /*创建问题*/
    public function add(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return ['status'=>0, 'msg'=>'用户未登录!'];
        $this->user_id = session('uid');

        /*检查标题是否为空,空返回,不为空存入quesion中*/
        if(!rq('title'))
            return ['status'=>0, 'msg'=>'问题标题不能为空!'];
        $this->title = rq('title');

        /*判断是否有问题描述,有存入question中,没有继续执行*/
        if(rq('desc'))
            $this->desc = rq('desc');

        /*使用laravel自带validation类进行传参合法性验证*/

        /*数据入库*/
        return $this->save() ?
            ['status'=>1, 'id'=>$this->id ] :
            ['status'=>0, 'msg'=>'问题添加失败!'];
    }


    /*更新问题*/
    public function change(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return ['status'=>0, 'msg'=>'用户未登录!'];

        /*判断是否传递了问题的id*/
        if(!rq('id'))
            return ['status'=>0, 'msg'=>'问题id不能为空'];

        /*判断问题是否存在*/
        $question = $this->is_question_exists(rq('id'));
        if(!$question)
            return ['status'=>0 ,'msg'=>'问题不存在!'];

        /*问题发起者才能修改*/
        if($question->user_id != session('uid'))
            return ['status'=>0, 'msg'=>'非问题发起者不能修改!'];

        /*是否修改了问题标题*/
        if(rq('title'))
            $question->title = rq('title');

        /*是否修改了问题描述*/
        !rq('desc') ? : $question->desc = rq('desc');
        //if(rq('desc'))
            //$question->desc = rq('desc');

        /*数据入库*/
        return $question->save() ?
            ['status'=>1] :
            ['status'=>0, 'msg'=>'问题更新失败'];
    }


    /*查看问题*/
    public function read(){
        /*判断是否含有对应id的问题,有返回对应id问题数据*/
        if(rq('id'))
            return ['status'=>1, 'data'=>$this->find(rq('id'))];

        /*不传递指定的问题id,返回查询到的所有问题*/
        /*$limit-设置一页显示数量*/
        $limit = rq('limit')?:15;
        /*$skip-设置偏移量(偏移页数),偏移值为1,跳过$limit的数量*/
        $skip = (rq('page')? rq('page') - 1: 0) * $limit;

        /*
         * 构建query并返回collection数据,laravel会将其转换为json对象
         * orderBy('col1','col2',..., 'DESC')-通过指定字段来排序
         * limit()-显示数量
         * skip()-偏移量
         * */
        $questions = $this
            ->orderBy('created_at', 'ASC')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'title', 'desc', 'user_id', 'created_at', 'updated_at'])
            ->keyBy('id');

        /**
         * get()-将所有查询条件转换成collection(与数组相似,是一个对象,具有强大的处理数组的功能)
         * get(['col1','col2'])-可指定取出的字段
         * keyBy()-以指定字段作为键,可将源get()所得的数组转换为json对象
        **/

        return ['status'=>1, 'data'=>$questions];
    }


    /*删除问题*/
    public function remove(){
        /*检查用户是否登录*/
        if(!user_ins()->is_signin())
            return ['status'=>0, 'msg'=>'用户未登录!'];

        /*判断是否传递了问题的id*/
        if(!rq('id'))
            return ['status'=>0, 'msg'=>'问题id不能为空'];

        /*判断问题是否存在*/
        $question = $this->is_question_exists(rq('id'));
        if(!$question)
            return ['status'=>0 ,'msg'=>'问题不存在!'];

        /*判断当前用户是否是问题发起者*/
        if(session('uid') != $question->user_id)
            return ['status'=>0, '不是问题发起者不能对该问题进行删除!'];

        /*数据删除*/
        return $question->delete() ?
            ['status'=>1] :
            ['status'=>0, 'msg'=>'问题删除失败!'];

    }


    /*检查问题是否存在*/
    public function is_question_exists($question_id){
        $question = $this->find($question_id);
        return $question ? : false;
    }

    public function test(){
        $a = 5;
        $b = 0;
        $c = 15;

//        $a = $b == 0? $b: $c;
        return $a ? : 10;

    }
}
