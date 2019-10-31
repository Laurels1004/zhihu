<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    /*时间线API*/
    public function timeline(){
        /*分页*/
        list($limit, $skip) = pagenate(rq('page'), rq('limit'));

        /*获取提问数据*/
        $questions = question_ins()
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'DESC')
            ->get();

        /*获取回答数据*/
        $answers = answer_ins()
            ->with('users')
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'DESC')
            ->get();

        /*数据合并 laravel中collection提供merge()方法合并两个collection对象*/
        $data = $questions->merge($answers);
        /*数据排序,sortBy()-用于排序,sortBy()方法会将每一条数据传给$item,放到方法中进行判断*/
        $data = $data->sortByDESC(function($item){
            return $item->created_at;
        });

        /*只要其中的值,而不要其中的键*/
        $data = $data->values()->all();

        /*可通过是否含有question_id这个字段判断是否是问题或回答*/
        return suc($data);
    }
}
