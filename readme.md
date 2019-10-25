# 2019/10/24
初始化Laravel5.2+Angular.js仿知乎

## 常规API调用规则
-所有的API都以'domain.com/api/xxx'开头
-API分为两部分,如'domain.com/part_1/part_2'
    -'part_1'为model名称,如user或question
    -'part_2'为行为名称(例如CRUD),如'reset_password'
-CRUD
    -每个model中都会有增删改查四个基础方法,分别对应add、read、change、remove

# 2019/10/25
Ver0.0.1-用户登录注册模块(后端)

##用户(User)模块API说明
### 用户注册: domain.com/user/signup
- 字段:
  - 'username':用户名
  - 'password':密码
  - 'id'&'uid'用户id
- 所需权限 -
- 传参: 'username'&&'password'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户名和密码不可为空!']
  -  ['status'=>0, 'msg'=>'用户名已存在!']
  -  ['status'=>0, 'msg'=>'用户注册失败!']
  -  ['status'=>1, 'id'=>用户id]

### 用户登录: user/signin
- 字段:
  - 'username':用户名
  - 'password':密码
  - 'id'&'uid'用户id
- 所需权限 -
- 传参: 'username'&&'password'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0 ,'msg'=>'用户已登录!']
  -  ['status'=>0, 'msg'=>'用户名和密码不可为空!']
  -  ['status'=>0, 'msg'=>'用户不存在!']
  -  ['status'=>0, 'msg'=>'用户密码错误!']
  -  ['status'=>0, 'msg'=>'用户注册失败!']
  -  ['status'=>1, 'id'=>用户id]

### 用户登出: user/logout
- 字段: - 
- 所需权限 -
- 传参 - 
- 返回状态及数据(0失败,1成功):
  -  ['status'=>1]
