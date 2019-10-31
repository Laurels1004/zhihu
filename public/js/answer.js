;(function(){
    'use strict';
    /*全局可见,不建议将多个变量放在window下,会产生变量污染*/
    window.his = {id:parseInt($('html').attr('user-id'))}
    angular.module('answer', [])
        .service('AnswerService',[
            '$http',
            function($http){
                var me = this;
                me.data = {};
                /*票数统计
                * @data array 用于统计票数的数据
                * 此数据可以是提问也可以是回答,如果是提问将跳过
                * */
                me.count_vote = function(data){
                    var i = 0;
                    var data_len = data.length;
                    /*迭代所有数据*/
                    for (i; i < data_len; i++) {
                        /*获取单个提问或回答数据*/
                        var item = data[i];
                        /*如果不是回答(没有question_id)或没有users元素(只有回答才拥有users元素)
                        * 说明当前数据不是回答或回答没有任何票数
                        * */
                        if(!item['question_id']) continue;
                        me.data[item.id] = item;
                        if(!item['users']) continue;
                        /*每条回答的默认赞同票和反对票都为0*/
                        item.upvote_count = 0;
                        item.downvote_count = 0;
                        /*users是所有投票用户的用户信息*/
                        var vote_users = item['users'];
                        var j = 0;
                        var vote_users_len = vote_users.length;
                        for (j; j < vote_users_len; j++) {
                            var vote = vote_users[j];
                            if(vote['pivot'].vote === 1)
                                /*为1自增赞同票*/
                                item.upvote_count++;
                            if(vote['pivot'].vote === 2)
                                /*为2自增反对票*/
                                item.downvote_count++;
                        }
                    }
                    return data;
                }

                /*AnserService中的点赞点踩方法*/
                me.vote = function(conf){
                    /*传参中如果没有id或vote方法则直接返回*/
                    if(!conf.id || !conf.vote){
                        console.log('id or vote are required');
                        return;
                    }

                    /*获取当前点击投票的回答实例*/
                    var answer = me.data[conf.id];
                    /*获取在当前回答下投票的用户*/
                    var users = answer.users;
                    /*遍历在当前回答下投票的用户*/
                    for(var i=0; i<users.length;i++){
                        /*如果当前遍历的用户id等于当前登录的用户id
                         *且当前遍历的用户投票字段等于传参过来的投票字段,则取消投票
                         * */
                        if(users[i].id == his.id &&
                            conf.vote == users[i].pivot.vote)
                            conf.vote = 3;
                    }


                    /*否则发送post请求到投票接口*/
                    return $http.post('/answer/vote', conf)
                        .then(function(s){
                            if(s.data.status)
                                return true;
                            return false;
                        },function(){
                            return false;
                        })
                }

                me.update_data = function(id){
                    return $http.post('answer/read', {id: id})
                        .then(function(s){
                            console.log(s.data.data);
                            me.data[id] = s.data.data;
                        })
                    /*批处理*/
                    /*angular.isNumeric()检测是否是数字*/
                    //if(angular.isNumeric(input))
                    //    var id = input;
                    //if(angular.isArray(input))
                    //    var id_set = input;

                }
            }]
        )
})();
