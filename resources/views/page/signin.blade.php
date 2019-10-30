{{--<script type="text/ng-template" id="signin.tpl">--}}
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
{{--</script>--}}
