;(function()
{
    'use strict';

    /*angular.module('angular应用名也就是ng-app',[其它模块名称,例如'ui.router'])*/
    angular.module('myApp', [
    'ui.router',
    'common',
    'user',
    'question',
    'answer'
    ])
    /* *
    * 注入依赖.config中的interpolateProvider,用于angular中的解析
    * ui.router需要两个服务:
    * $stateProvide和$urlRouterProvider
    * */
    /*传递数组可避免js被压缩导致的变量识别错误的问题,字符串不会被压缩和混淆*/
        .config(['$interpolateProvider',
                 '$stateProvider',
                 '$urlRouterProvider',
                 function ($interpolateProvider,
                          $stateProvider,
                          $urlRouterProvider)
            {
            /*配置angular,由于angular和laravel中使用模板变量的方式是一样的 {{  }}*/
            /*指定变量解析开始符*/
            $interpolateProvider.startSymbol('[:');
            /*指定变量解析结束符*/
            $interpolateProvider.endSymbol(':]');

            /*没有定义路由规则时*/
            $urlRouterProvider.otherwise('/home');

            /*定义路由规则*/
            $stateProvider
                .state('home', {
                    /*定义首页,设置参数*/
                    /*地址栏url设置*/
                    url: '/home',
                    /*需要的html模板url,如果没有在所有tpl中找不到就会去服务器中查找*/
                    // templateUrl: 'home.tpl'
                    /*异步请求,而不是一次下发的页面*/
                    templateUrl: 'tpl/page/home'
                })
                .state('signin', {
                    /*地址栏url设置*/
                    url: '/signin',
                    /*需要的html模板*/
                    //template: '登录'
                    // templateUrl: 'signin.tpl'
                    templateUrl: 'tpl/page/signin'
                })
                .state('signup', {
                    /*地址栏url设置*/
                    url: '/signup',
                    /*需要的html模板*/
                    //template: '登录'
                    // templateUrl: 'signup.tpl'
                    templateUrl: 'tpl/page/signup'
                })
                /*隐藏question可见状态*/
                .state('question', {
                    /*定义为抽象路由,隐藏可见状态*/
                    abstract:true,
                    /*地址栏url设置*/
                    url: '/question',
                    /*需要template才能插入路由*/
                    template: '<div ui-view=""></div>'
                })
                .state('question.add', {
                    /*地址栏url设置,访问这个时就查看parent是否含有template且template中是否含有ui-view
                    * 如果parent中的template含有ui-view,才会插入子路由中的模板
                    * */
                    url: '/add',
                    /*需要的html模板*/
                    //template: '登录'
                    // templateUrl: 'question.add.tpl'
                    templateUrl: 'tpl/page/question_add'
                })
            }]
        )
})();

