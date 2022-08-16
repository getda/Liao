### Liao
#### 环境
* PHP 7.4+ 
* Mysql 5.7+
* Redis
### 部署说明
1. 克隆代码并安装扩展
```shell
git clone git@github.com:getda/Liao.git liao
cd liao
composer install
```

2. 将 `config` 目录下的 `.env.example.php` 重命名为 `.env.php` 并配置里面的相应信息
```php
<?php
/*
|--------------------------------------------------------------------------
| Base
|--------------------------------------------------------------------------
*/
$app = [
    'DEBUG'            => false,
    // web 服务
    'SERVER_LISTEN'    => "http://0.0.0.0:8787",
    // socket 服务
    'WEBSOCKET_LISTEN' => "websocket://0.0.0.0:8282",
];

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/
$database = [
    'DB_CONNECTION' => 'mysql',
    'DB_HOST'       => '127.0.0.1',
    'DB_PORT'       => '3306',
    'DB_DATABASE'   => 'test',
    'DB_USERNAME'   => 'root',
    'DB_PASSWORD'   => '',
];

/*
|--------------------------------------------------------------------------
| Redis
|--------------------------------------------------------------------------
*/
$redis = [
    'REDIS_HOST'     => '127.0.0.1',
    'REDIS_PORT'     => 6379,
    'REDIS_DATABASE' => 0,
    'REDIS_PASSWORD' => null,
];

return array_merge($app, $database, $redis);
```
3. 执行数据表迁移
```shell
php vendor/bin/phinx migrate
```
4. 启动服务
```shell
# 调试
php start.php start
# 生成环境
php start.php start -d
```

5. 配置域名
> 见：[https://www.workerman.net/doc/webman/others/nginx-proxy.html](https://www.workerman.net/doc/webman/others/nginx-proxy.html)