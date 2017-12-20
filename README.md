##  phalcon-restful
A clear, simple and efficient phalcon restful framework based on phalcon3.2.   
`s`

### 概览
#### 1. 文件目录
```
├── app
│   ├── config                      // 配置
│   │   ├── dev.ini
│   │   └── prd.ini
│   ├── controllers                 // 控制器
│   │   ├── ControllerBase.php
│   │   ├── DishController.php
│   │   ├── IndexController.php
│   │   ├── TokenController.php
│   │   └── UserController.php
│   ├── events                      // 事件
│   │   └── AppEvents.php
│   ├── exceptions                  // 异常处理
│   │   ├── AuthException.php
│   │   ├── DbException.php
│   │   ├── HttpException.php
│   │   └── ValidationException.php
│   ├── forms                       // 表单验证
│   │   ├── FormBase.php
│   │   ├── FormForDish.php
│   │   └── FormForToken.php
│   ├── models                      // 模型
│   │   ├── DishModel.php
│   │   ├── PrivilegeModel.php
│   │   ├── UserModel.php
│   │   └── UserRoleModel.php
│   ├── utils                       // 工具类
│   │   ├── AppRouter.php
│   │   ├── Collection.php
│   │   ├── Debug.php
│   │   ├── Http.php
│   │   └── Utils.php
│   ├── bootstrap.php               // 初始化
│   ├── resources.php               // 资源注册
│   └── services.php                // 系统服务
├── cache                           // 文件缓存目录
│   └── session
├── migration                       // 数据迁移
│   └── 1.0.0.sql
├── public
│    └── index.php                  // web入口脚本
└── cli.php                         // 命令行入口脚本
```

#### 2. 演示实例
```
# 获取 _token
curl -XGET [domain]/_token?name=18966668888&password=123456

# 注销 _token
curl -XDELETE [domain]/_token

# 菜单列表
curl -XGET [domain]/dishes

# 菜单详情
curl -XGET [domain]/dishes/1

# 添加菜单
curl -XPOST [domain]/dishes -d '{"name":"鱼香肉丝", "price":20}'

# 更新菜单
curl -XPATCH [domain]/dishes/1 -d '{"price":18}'

# 删除菜单
curl -XDELETE [domain]/dishes/1
```

#### 3. 权限控制
将用户划分到不同的角色, 每一种角色对每一个资源的各种操作权限都可以控制。具体配置参见`mysql(DB)\privilege(Table)`具体代码实现参见`AppEvents->guard()`。


#### 4. 表单验证
表单验证是服务端很重要的一部分, 能有效防止SQL注入攻击、使逻辑更加严谨、优化服务体验。而这往往也是最另开发者头疼的一部分, 往往验证逻辑代码纷繁杂乱, 不堪入目。
本项目提供表单验证类, 不依赖任何第三方库, 方便使用, 灵活扩展。具体使用参见`DishController->create()`。


#### 5. 辅助参数
`_token`可以代替基于浏览器的cookie, 适用于非浏览器环境的客户端。   
`_method`可以强制改变请求方法, 适用于调试等一些特殊情况。


### 部署
#### 1.环境
推荐linux + nginx + php5.5~5.9 + mysql5.6+, 如何安装php phalcon3.2扩展, 参见 [https://phalconphp.com/zh/download/linux](https://phalconphp.com/zh/download/linux)。

#### 2.克隆代码
克隆或下载本项目代码到您的www目录下。

#### 3.导入数据库
在mysql中新建数据库, 将`/migration/1.0.0.sql`导入新建的数据库。

#### 4.配置   

##### (1) nginx 配置参考, 重点是"@rewrite"配置
```
server {
    listen       80;
    server_name  <server_name>;

    root   <app_path>/public;
    index  index.html index.htm index.php;

    try_files $uri $uri/ @rewrite;
    
    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }
    
    location ~ \.php$ {
        try_files $uri = 404;
        include fastcgi.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```
##### (2) 配置
`/app/config/prd.ini`是生产环境配置文件, `/app/config/dev.ini`是开发环境配置文件, 后者会覆盖前者。