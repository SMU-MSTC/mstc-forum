# MSTC Forum

[简体中文](README-zh_CN.md)

## Introduction
This is a simple forum written in PHP and Vue.js.
- Frontend: Vue.js & Vue Router
- Backend: PHP
- Database: PostgreSQL

## Project setup

### Install npm dependencies
```
$ npm install
```

### Server Configuration
Here is a sample of the configuration file for Nginx.
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

### Create database
Please make sure that you have already installed PostgreSQL.
```
$ createdb forum
```
Tables will be created when the website is visited for the first time.

### PHP configuration
Configure ```api/config.example.php``` to connect the database and rename it to ```config.php```.

Make sure ```display_error=off``` in ```php.ini```.

### Initialize database
When the configuration is completed, you can manually access ```http(s)://forum.localhost/api/``` to initialize the tables in the database.

After tables are created, you can run ```Init.sql``` to insert some initial data into the database.
```
$ psql -d forum -f /path/to/Init.sql
```
At least, you need to create a user named 'admin'.

### Run server
```
$ npm run serve
```
Then you can access ```http(s)://forum.localhost```.

### Customize configuration
If you want to change ```server_name```, don't forget to change the value of variable ```global.api``` in ```src/main.js```.

## Demo page
Here is a demo view https://forum.airstone.me .

You can log in as user ```admin``` with password ```admin```, and your changes will not be saved in the database.
