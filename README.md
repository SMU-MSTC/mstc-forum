# Forum Demo

## Intro
This is a simple demo of forum written in PHP and Vue.js, front-end separated from back-end.
- Front-end: vue-cli, vue-router
- Back-end: PHP
- Database: Postgresql

## Project setup

### Install npm dependencies
```
$ npm install
```

### Server Configuration
Here is a sample of configuration file for nginx.
```
server {

    server_name     forum.localhost;
    root    /path/to/php-vue-forum-demo/src/;

    # For php files
    location ~ /api/(.*) {
        # pass all requests to index.php
        try_files $uri $uri/ /api/index.php?$query_string;

        # php-fpm configuration, probably platform dependent
        fastcgi_pass   unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    # For webpage
    location / {
        # reverse proxy of node.js server
        proxy_pass http://localhost:8080;
    }

}
```

### Create database
Please make sure that you have already installed postgresql.
```
$ createdb forum
```
Tables will be created when the website is visited for the first time.

### PHP database connection configuration
Configure ```./src/api/config.example.php```  and rename it to ```config.php```.


### Initialize database
When the configuration is completed, you can manually access ```http(s)://forum.localhost/api/``` to initialize the tables in database.

After tables are created, you can run ```./src/Init.sql``` to insert some initial data into database.
```
$ psql -d forum -f /path/to/src/Init.sql
```
At least, you need to create a user named 'admin'.

### Compiles and run
```
$ npm run serve
```
Then you can access ```http(s)://forum.localhost```.

### Customize configuration
If you want to change ```server_name```, don't forget to change ```global.api``` in ```./src/main.js```.
