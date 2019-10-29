<!doctype html>
{{--ng-app-声明angular应用名,模块名称--}}
<html lang="zh" ng-app="myApp">
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
      <div class="navbar-item brand">知乎</div>
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
      <div ui-sref="signin" class="navbar-item">{{ session('username') }}</div>
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
<script type="text/ng-template" id="home.tpl">
{{--ng-template中必须有一个最大的元素包含其它元素--}}
 <div class="home container">
    首页
 </div>
</script>
<script type="text/ng-template" id="signin.tpl">
 <div ng-controller="SigninController" class="signin container">
        <div class="card">
            <h1>登录</h1>
            [: User.signin_data :]
            {{--angular中提交表单不需要form,只需要ng-submit--}}
            <form name="signin_form" ng-submit="User.signin()">
                <div class="input-group">
                    <lable>用户名: </lable>
                    {{--ng-model-域下的模块;ng-minlength-最小长度,ng-model-options="{debounce: 500}"延时数据绑定--}}
                    <input name="username" type="text" ng-model="User.signin_data.username" ng-minlength="4" ng-maxlength="24" required ng-model-options="{debounce: 500}">
                    {{--触碰过该div后才执行--}}
                    <div class="input-error-set" ng-if="signin_form.username.$touched">
                        <div ng-if="signin_form.username.$error.required">用户名为必填项</div>
                        <div ng-if="signin_form.username.$error.maxlength || signin_form.username.$error.minlength">用户名长度需在4至24位之间</div>
                        <div ng-if="User.signin_username_exist">用户名不存在</div>
                    </div>
                </div>
                <div class="input-group">
                    <lable>密&nbsp;&nbsp;码: </lable>
                    <input name="password" type="password" ng-model="User.signin_data.password" ng-minlength="6" ng-maxlength="255" required>
                    <div class="input-error-set" ng-if="signin_form.password.$touched">
                        <div ng-if="signin_form.password.$error.required">密码为必填项</div>
                        <div ng-if="signin_form.password.$error.maxlength || signin_form.password.$error.minlength">密码长度需在6至255位之间</div>
                    </div>
                </div>
                <div ng-if="User.signin_failed" class="input-error-set">用户名或密码错误</div>
                {{--ng-disabled-功能禁用,表单非法时禁用--}}
                <button class="primary" type="submit" ng-disabled="signin_form.$invalid">登录</button>
            </form>
        </div>
 </div>
</script>
<script type="text/ng-template" id="signup.tpl">
    {{--页面中用到数据的域都需要有controller--}}
    <div ng-controller="SignupController" class="signup container">
        <div class="card">
            <h1>注册</h1>
            [: User.signup_data :]
            {{--angular中提交表单不需要form,只需要ng-submit--}}
            <form name="signup_form" ng-submit="User.signup()">
                <div class="input-group">
                    <lable>用户名: </lable>
                    {{--ng-model-域下的模块;ng-minlength-最小长度,ng-model-options="{debounce: 500}"延时数据绑定--}}
                    <input name="username" type="text" ng-model="User.signup_data.username" ng-minlength="4" ng-maxlength="24" required ng-model-options="{debounce: 400}">
                    {{--触碰过该div后才执行--}}
                    <div class="input-error-set" ng-if="signup_form.username.$touched">
                        <div ng-if="signup_form.username.$error.required">用户名为必填项</div>
                        <div ng-if="signup_form.username.$error.maxlength || signup_form.username.$error.minlength">用户名长度需在4至24位之间</div>
                        <div ng-if="User.signup_username_exists">用户名已存在</div>
                    </div>
                </div>
                <div class="input-group">
                    <lable>密&nbsp;&nbsp;码: </lable>
                    <input name="password" type="password" ng-model="User.signup_data.password" ng-minlength="6" ng-maxlength="255" required>
                    <div class="input-error-set" ng-if="signup_form.password.$touched">
                        <div ng-if="signup_form.password.$error.required">密码为必填项</div>
                        <div ng-if="signup_form.password.$error.maxlength || signup_form.password.$error.minlength">密码长度需在6至255位之间</div>
                    </div>
                </div>
                {{--ng-disabled-功能禁用,表单非法时禁用--}}
                <button type="submit" ng-disabled="signup_form.$invalid">注册</button>
            </form>
        </div>
    </div>
</script>
<script type="text/ng-template" id="question.add.tpl">
    <div ng-controller="QuestionAddController" class="question-add container">
        <div class="card">
            <form name="question_add_form" ng-submit="Question.add()">
                <div class="input-group">
                    <lable>提问标题</lable>
                    {{--这里的ng-model与上面的question.new_question.title调用的是同一个数据--}}
                    <input type="text" name="title" ng-model="Question.new_question.title" required minlength="5" maxlength="255">
                </div>
                <div class="input-group">
                    <lable>提问描述</lable>
                    <textarea type="text" name="desc" ng-model="Question.new_question.desc">
                    </textarea>
                </div>
                <div class="input-group">
                    {{--<button ng-disabled="question_add_form.title.$error.required" type="submit" class="primary">提交</button>--}}
                    <button ng-disabled="question_add_form.$invalid" type="submit" class="primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</script>
</html>
