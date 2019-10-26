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
- 所需权限: -
- 传参: 必填 'username'&&'password'
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
- 所需权限: -
- 传参: 必填 'username'&&'password'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0 ,'msg'=>'用户已登录!']
  -  ['status'=>0, 'msg'=>'用户名和密码不可为空!']
  -  ['status'=>0, 'msg'=>'用户不存在!']
  -  ['status'=>0, 'msg'=>'用户密码错误!']
  -  ['status'=>0, 'msg'=>'用户注册失败!']
  -  ['status'=>1, 'id'=>用户id]

### 用户登出: user/logout
- 字段: - 
- 所需权限: -
- 传参 - 
- 返回状态及数据(0失败,1成功):
  -  ['status'=>1]


## 二、用户提问(Question)模块API说明
### 创建提问: domain.com/question/add
- 字段:
  - 'title': 提问标题
  - 'desc': 提问描述
- 所需权限: 用户登录
- 传参: 必填:'title'; 可选:'desc'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'提问标题不能为空']
  -  ['status'=>0, 'msg'=>'提问添加失败']
  -  ['status'=>1]

### 更新提问: domain.com/question/change
- 字段:
  - 'id': 提问id
  - 'title': 提问标题
  - 'desc': 提问描述
- 所需权限: 用户登录且为提问发起者
- 传参: 必填:'id'; 可选:'title','desc'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'提问id不能为空']
  -  ['status'=>0 ,'msg'=>'提问不存在!']
  -  ['status'=>0, 'msg'=>'非提问发起者不能修改!']
  -  ['status'=>0, 'msg'=>'提问更新失败']
  -  ['status'=>1]

### 查看提问: question/read
- 字段:
  - 'id':提问id
  - 'limit': 每页显示数量
  - 'page': 页码
- 所需权限: -
- 传参: 可选:'id';
- 返回状态及数据(0失败,1成功):
  -  ['status'=>1, 'data'=>指定id的提问数据]
  -  ['status'=>1, 'data'=>所有的提问数据]

### 删除提问: question/remove
- 字段:
  - 'id':提问id
- 所需权限: 用户登录且该用户是提问发起者
- 传参: 必填:'id';
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'提问id不能为空']
  -  ['status'=>0 ,'msg'=>'提问不存在!']
  -  ['status'=>0, '不是提问发起者不能对该提问进行删除!']
  -  ['status'=>0, 'msg'=>'提问删除失败!']
  -  ['status'=>1]


# 2019/10/26
Ver0.0.3-用户回答模块(后端)

## 三、回答(Answer)模块API说明
### 创建回答: domain.com/answer/add
- 字段:
  - 'content':回答内容
  - 'question_id':回答对应的提问ID
- 所需权限: 用户登录,一个提问下用户不能重复回答
- 传参: 必填 'content'&&'question_id'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'提问ID和用户回答内容不能为空!']
  -  ['status'=>0, 'msg'=>'提问不存在!']
  -  ['status'=>0, 'msg'=>'一个提问下用户不能重复回答!']
  -  ['status'=>0, 'msg'=>'回答创建失败!']
  -  ['status'=>1, 'id'=>回答ID]

### 更新回答: domain.com/answer/change
- 字段:
  - 'content':回答内容
  - 'id':回答ID
- 所需权限: 用户登录,回答发起者
- 传参: 必填 'content'&&'id'
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'用户未登录!']
  -  ['status'=>0, 'msg'=>'回答ID和回答内容不能为空']
  -  ['status'=>0, 'msg'=>'回答不存在!']
  -  ['status'=>0, 'msg'=>'非回答发起者不能对该回答进行修改!']
  -  ['status'=>0, 'msg'=>'回答更新失败!']
  -  ['status'=>1]

### 查看回答: domain.com/answer/read
- 字段:
  - 'id':回答ID
  - 'question_id':回答对应的提问ID
- 所需权限: -
- 传参: 必填 'id' || 'question_id',二选一,不能全为空
- 返回状态及数据(0失败,1成功):
  -  ['status'=>0, 'msg'=>'回答ID或提问ID不能为空!']
  -  ['status'=>0, 'msg'=>'回答不存在']
  -  ['status'=>0, 'msg'=>'提问不存在!']
  -  ['status'=>1, 'data'=>某个回答数据]
  -  ['status'=>0, 'msg'=>'该问题下还没有回答!']
  -  ['status'=>1, 'data'=>某个问题下的所有回答数据]

### 删除回答: -
