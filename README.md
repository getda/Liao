### Liao
#### 环境
* PHP 7.4+ 
* Mysql 5.7+
* Redis
#### 数据库
config/.env.php
#### Redis
config/redis.php
#### 数据迁移
配置文件： `phinx.php`

1. 迁移：
```
php vendor/bin/phinx migrate
```
2. 回滚：
```
php vendor/bin/phinx rollback
```