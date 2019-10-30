;(function(){
    'use strict';
    angular.module('question',[])
        .service('QuestionService',[
            '$state',
            '$http',
            function($state, $http){
                var me = this;
                me.new_question = {};

                me.go_add_question = function(){
                    /*当前端页面点击提问按钮时,路由跳转到question.add也就是显示question.add.tpl*/
                    $state.go('question.add');
                }

                me.add = function(){
                    console.log(1);
                    /*没有question标题直接返回*/
                    if (!me.new_question.title)
                        return;

                    /*否则发送ajax请求添加提问*/
                    $http.post('/question/add',me.new_question)
                        .then(function (s) {
                            if(r.data.status){
                                /*数据重置*/
                                me.new_question = {};
                                $state.go('home');
                            } else {

                            }
                            console.log(s);
                        },function(e){
                            console.log(e);
                        })
                }
            }]
        )
        .controller('QuestionAddController',[
            '$scope',
            'QuestionService',
            function($scope, QuestionService){
                $scope.Question = QuestionService;
            }]
        )
})();
