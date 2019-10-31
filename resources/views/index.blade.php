<!doctype html>
{{--ng-app-声明angular应用名,模块名称--}}
<html lang="zh" ng-app="myApp" user-id="{{ session('uid') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>zhihu</title>
    {{--本地资源--}}
    {{--开发时建议使用angular.js而不是angular.min.js,--}}
    <link rel="stylesheet" href="/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/jquery/dist/jquery.js"></script>
    <script src="/angular/angular.js"></script>
    <script src="/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/user.js"></script>
    <script src="/js/question.js"></script>
    <script src="/js/answer.js"></script>
    {{--CND资源--}}
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/normalize/3.0.3/normalize.css">--}}
    {{--<link rel="stylesheet" href="/css/base.css">--}}
    {{--<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>--}}
    {{--<script src="https://cdn.bootcss.com/angular.js/1.4.6/angular.js"></script>--}}
    {{--<script src="https://cdn.bootcss.com/angular-ui-router/0.2.15/angular-ui-router.js"></script>--}}
    {{--<script src="/js/base.js"></script>--}}
</head>
<body>
<div class="navbar clearfix">
  <div class="container">
    <div class="fl">
      <div ui-sref="home" class="navbar-item brand">知乎</div>
          {{--ng-submit:表单提交之后到QuestionService中查找go_add_question方法,这个方法执行页面跳转功能--}}
          <form ng-submit="Question.go_add_question()" id="quick-ask" ng-controller="QuestionAddController">
              <div class="navbar-item">
                    {{--在导航栏提出问题后,跳转到提问详情页面,并把input框的内容传递过去--}}
                    <input type="text" ng-model="Question.new_question.title">
              </div>
              <div class="navbar-item">
                    <button>提问</button>
              </div>
          </form>
    </div>
    <div class="fr">
      <div ui-sref="home" class="navbar-item">首页</div>
      @if(is_signin())
      <div class="navbar-item">{{ session('username') }}</div>
      <a href="{{ url('/user/logout') }}" class="navbar-item">登出</a>
      @else
      <div ui-sref="signin" class="navbar-item">登录</div>
      <div ui-sref="signup" class="navbar-item">注册</div>
      @endif
    </div>
  </div>
</div>
<div class="page">
  <div ui-view></div>
</div>
</body>
</html>
