;(function(){
    'user strict';
    angular.module('common', [])
        .service('TimelineService',[
            '$http',
            function($http){
                var me = this;
                me.data = [];
                me.current_page = 1;

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
            }]
        )
        .controller('HomeController',[
            '$scope',
            'TimelineService',
            function($scope, TimelineService){
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
                    if($win.scrollTop() - ($(document).height() - $win.height()) > -50){
                        TimelineService.get();
                    }

                })
            }]
        )
})();
