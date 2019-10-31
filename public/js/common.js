;(function(){
    'user strict';
    angular.module('common', [])
        .service('TimelineService',[
            '$http',
            'AnswerService',
            function($http, AnswerService){
                var me = this;
                me.data = [];
                me.current_page = 1;

                /*获取首页数据*/
                me.get = function(conf)
                {
                    /*
                    * pending在表单字段做异步校验时，pending是true
                    * 此时，可以在界面上对此异步校验做出提示
                    * */
                    /*pending存在,请求拒绝发送*/
                    if(me.pending) return;
                    me.pending = true;
                    /*设置传参*/
                    conf = conf || {page: me.current_page}
                    $http.post('/timeline',conf)
                        .then(function(s){
                            if(s.data.status){
                                /*如果有长度*/
                                if(s.data.data.length){
                                    me.data = me.data.concat(s.data.data);
                                    /*统计每一条回答的票数*/
                                    me.data = AnswerService.count_vote(me.data);
                                    /*每次get都应该记录上一次get的页码*/
                                    me.current_page++;
                                } else {
                                    me.no_more_data = true;
                                }
                            } else {
                                console.log('network error');
                            }
                        }, function(){
                            console.log('network error');
                        })
                        .finally(function(){
                            /*finally和jq中的always一样*/
                            /*服务器返回错误,pending设置为false*/
                            me.pending = false;
                        })
                }

                /*点击主页时间线上的赞踩按钮触发事件*/
                me.vote = function(conf){
                    /*
                    * 调用核心投票功能
                    * conf-{id: , vote: }
                    * 传递参数到AnswerService的投票(vote())方法中
                    * */
                    AnswerService.vote(conf)
                        .then(function(s){
                            /*如果投票功能执行成功-返回s(true)
                            * 此时执行AnswerService中数据更新方法update_data()
                            * 传值-要更新的回答id
                            * */
                            if(s) AnswerService.update_data(conf.id);
                        })
                }
            }]
        )
        .controller('HomeController',[
            '$scope',
            'TimelineService',
            'AnswerService',
            function($scope, TimelineService, AnswerService){
                var $win;
                $scope.Timeline = TimelineService;
                /*直接获取数据*/
                TimelineService.get();

                $win = $(window);
                /*列表信息自动加载-窗口滚动监听*/
                $win.on('scroll',function(){
                    /*
                    * scrollTop()-文档滚动高度
                    * $(document).height()-整个文档高度
                    * $win.height()-当前窗口高度
                    * 也就是当滚动条距离底部不远的地方才会触发事件-数据刷新
                    * */
                    if($win.scrollTop() - ($(document).height() - $win.height()) > -20){
                        TimelineService.get();
                    }
                })

                /*$scope.$watch-监控数据变化,监控点赞点踩数据*/
                $scope.$watch(function(){
                    /*监控AnserService中的数据变化*/
                    return AnswerService.data;
                },function(new_data, old_data){
                    /*当AnserService数据发生变化,则执行迭代,n-新数据,o-旧数据*/
                    /*将AnserService中的数据全部提出来与TimelineService中的数据进行比对
                    * 看时间线中的数据有没有answer中的数据,有则更新
                    * */
                    var timeline_data = TimelineService.data;
                    for(var k in new_data){
                        for(var i=0; i < timeline_data.length; i++){
                            /*这里的k是n[k].id*/
                            if(k == timeline_data[i].id){
                                timeline_data[i] = new_data[k];
                            }
                        }
                    }

                    TimelineService.data = AnswerService.count_vote(TimelineService.data)
                },true)
            }]
        )
})();
