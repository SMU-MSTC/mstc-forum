# MSTC Forum

[English](README.md)

## 简介
一个由 PHP 和 Vue.js 编写的简易论坛。
- 前端： Vue.js & Vue Router
- 后端： PHP
- 数据库： PostgreSQL

## 设置

### 安装 npm 依赖
```
$ npm install
```

### 服务器配置
这是一份 Nginx 配置文件的样例。
```
server {

    server_name     forum.localhost;
    root    /path/to/forum/;

    # For PHP files
    location ~ /api/(.*) {
        # pass all requests to api/index.php
        try_files $uri $uri/ /api/index.php?$query_string;

        # php-fpm configuration, probably platform dependent
        fastcgi_pass   unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    # For Vue.js webpages
    location / {
        # reverse proxy of Node.js server
        proxy_pass http://localhost:8080;
    }

}
```

### 新建数据库
请确认你已经安装了 PostgreSQL 。
```
$ createdb forum
```
当第一次访问网站时，数据库中的表将会自动创建。

### PHP 配置
设置 ```api/config.example.php``` 以连接到数据库，并将其重命名为 ```config.php``` 。

在 ```php.ini``` 中设置 ```display_error=off``` 关闭错误提示。

### 数据库初始化
配置完成后，你可以手动访问 ```http(s)://forum.localhost/api/``` 以初始化数据库中的表。

当数据库的表建立完后，你可以运行 ```Init.sql``` 将预设数据添加到数据库。
```
$ psql -d forum -f /path/to/Init.sql
```
至少，你应该创建一个名为 admin 的用户。

### 运行服务器
```
$ npm run serve
```
之后你就可以访问 ```http(s)://forum.localhost``` 。

### 个性设置
如果你要修改 ```server_name``` ，别忘了修改 ```src/main.js``` 中的变量 ```global.api``` 。

## 预览
这是网站预览 https://forum.airstone.me 。

你可以使用 ```admin``` 登录，密码是 ```admin``` ，你所做的更改只会暂时保存到服务器中。
