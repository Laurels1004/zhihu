{{--<script type="text/ng-template" id="signup.tpl">--}}
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
{{--</script>--}}
