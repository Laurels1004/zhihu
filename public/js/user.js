;(function(){
    'use strict';
    angular.module('user',[])
        /*定义服务,所有与User模块相关的功能及数据都放置于此*/
        .service('UserService',[
            '$state',
            '$http',
            /*添加$state(用于页面跳转)与$http(用于发送请求)*/
            function($state, $http){
                /*this就等于当前UserService这个服务,之后的注册控制器会注入这个服务依赖*/
                var me = this;
                /*存储注册服务的数据*/
                me.signup_data = {};
                /*注册按钮绑定事件*/
                me.signup = function()
                {
                    $http.post('/user/signup', me.signup_data)
                        /*成功返回的数据*/
                        .then(function(s){
                            console.log('s', s);
                            if(s.data.status)
                            {
                                /*注册成功,清除数据*/
                                me.signup_data = {}
                                /*跳转至登录页*/
                                /*state(url, params, {reload: true});跳转到指定的url状态，最后传递参数，
                                * reload为true表示会重载,详见ui-router API
                                * */
                                $state.go('signin');
                            }
                        /*失败返回的数据*/
                        },function(e){
                            console.log('e', e);
                        })
                }

                /*存储登录服务的数据*/
                me.signin_data = {};
                /*登录按钮绑定事件*/
                me.signin = function(){
                    $http.post('/user/signin', me.signin_data)
                        /*成功返回的数据*/
                        .then(function(s){
                            console.log('s', s);
                            if(s.data.status)
                            {
                                /*注册成功,清除数据*/
                                me.signin_data = {}
                                /*state(url, params, {reload: true});跳转到指定的url状态，最后传递参数，
                                * reload为true表示会重载,详见ui-router API
                                * */
                                /*跳转至登录页*/
                                $state.go('home');
                                location.href = '/';
                            }else{
                                me.signin_failed = true;
                            }
                        /*失败返回的数据*/
                        },function(e){
                            console.log('e', e);
                        })
                }

                /*检查用户名是否存在*/
                me.username_exists = function(){
                    $http.post('/user/is_uname_exist',
                        {username: me.signup_data.username})
                        .then(function(s){
                            if(s.data.status && s.data.data.count){
                            console.log('s', s);
                                me.signup_username_exists = true;
                            }else{
                                me.signup_username_exists = false;
                            }
                        }, function(e){
                            console.log('e', e);
                        });
                }
                /*检查用户名是否存在*/
                me.username_exist = function(){
                    $http.post('/user/is_uname_exist',
                        {username: me.signin_data.username})
                        .then(function(s){
                            if(s.data.status && s.data.data.count){
                            console.log('s', s);
                                me.signin_username_exist = false;
                            }else{
                                me.signin_username_exist = true;
                            }
                        }, function(e){
                            console.log('e', e);
                        });
                }
        }])

        /*
        * angular中的控制器,是一个域的所有者
        * 我们将ng-app指定在哪个标签上,哪个标签以下的标签都会有一个根域
        * $rootScope-一切都是从根域继承下来的,一个应用存在很多域
        * */
        .controller('SignupController', [
            '$scope',
            'UserService',
            function($scope, UserService)
            {
                /*Controller(scope)作用域继承了rootScope
                * rootScope可见的这个scope不一定可见
                * 这个域作用在哪取决于将ng-controller添加在哪个标签上
                * 标签下的子元素也会共享这个域
                * */
                /*将域.User声明为上面的UserService服务,HTML中form表单ng-submit="User.signup()"中的User就是该域的User*/
                $scope.User = UserService;
                /*数据监控,param1-监控对象,param2-数据发生变化时的操作,param3-true递归检查每一次,每一次出现变化则执行*/
                $scope.$watch(function(){
                    /*返回监控的内容给User服务中的signup_data*/
                    return UserService.signup_data;
                }, function(n, o){
                    /*监控数据变化时的操作n代表新输入的数据,o代表之前输入的数据*/
                    if(n.username != o.username)
                        /*触发User服务中的username_exists判断用户名是否存在*/
                        UserService.username_exists();
                },true)
            }
        ])
        .controller('SigninController',[
            '$scope',
            'UserService',
            function($scope, UserService)
            {
                $scope.User = UserService;
                $scope.$watch(function(){
                    return UserService.signin_data;
                }, function(n,o){
                    if(n.username != o.username)
                        UserService.username_exist();
                },true)
            }
        ])
})();
