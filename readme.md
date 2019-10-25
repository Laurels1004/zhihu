# 2019/10/24
初始化Laravel5.2+Angular.js仿知乎

## 常规API调用规则
- 所有的API都以'domain.com/api/xxx'开头
- API分为两部分,如'domain.com/part_1/part_2'
    - 'part_1'为model名称,如user或question
    - 'part_2'为行为名称(例如CRUD),如'reset_password'
- CRUD
    -每个model中都会有增删改查四个基础方法,分别对应add、read、change、remove

# 2019/10/25
Ver0.0.2-用户登录注册模块(后端)

## 一、用户(User)模块API说明
### 用户注册: domain.com/user/signup
- 字段:
  - 'username':用户名
  - 'password':密码
  - 'id'&'uid'用户id
- 所需权限 -
- 传参: 'username'&&'password'必填
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
- 传参: 'username'&&'password'必填
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

## 二、问题(Question)模块API说明
### 创建问题: domain.com/question/add
- 字段:
  - 'title': 问题标题
  - 'desc': 问题描述
- 所需权限: 用户登录
- 传参: 必填:'title'; 可选:'desc'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'问题标题不能为空']
  -  ['status'=>0, 'msg'=>'问题添加失败']
  -  ['status'=>1]

### 更新问题: domain.com/question/change
- 字段:
  - 'id': 问题id
  - 'title': 问题标题
  - 'desc': 问题描述
- 所需权限: 用户登录且为问题发起者
- 传参: 必填:'id'; 可选:'title','desc'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'问题id不能为空']
  -  ['status'=>0 ,'msg'=>'问题不存在!']
  -  ['status'=>0, 'msg'=>'非问题发起者不能修改!']
  -  ['status'=>0, 'msg'=>'问题更新失败']
  -  ['status'=>1]


### 查看问题: question/read
- 字段:
  - 'id':问题id
  - 'limit': 每页显示数量
  - 'page': 页码
- 所需权限 -
- 传参: 可选:'id';
- 返回状态及数据(0失败,1成功):
  -  ['status'=>1, 'data'=>指定id的问题数据]
  -  ['status'=>1, 'data'=>所有的问题数据]

### 删除问题: question/remove
- 字段:
  - 'id':问题id
- 所需权限 用户登录且该用户是问题发起者
- 传参: 必填:'id';
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'问题id不能为空']
  -  ['status'=>0 ,'msg'=>'问题不存在!']
  -  ['status'=>0, '不是问题发起者不能对该问题进行删除!']
  -  ['status'=>0, 'msg'=>'问题删除失败!']
  -  ['status'=>1]