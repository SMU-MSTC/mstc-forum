<?php

class Core
{

    public $config, $connection;

    public function __construct()
    {
        spl_autoload_register(function ($class_name) {
            if (is_file(__DIR__ . "/" . $class_name . ".class.php"))
                require_once __DIR__ . "/" . $class_name . ".class.php";
            else if (is_file(__DIR__ . "/model/" . $class_name . ".class.php"))
                require_once __DIR__ . "/model/" . $class_name . ".class.php";
            else if (is_file(__DIR__ . "/controller/" . $class_name . ".class.php"))
                require_once __DIR__ . "/controller/" . $class_name . ".class.php";
            else if (is_file(__DIR__ . "/view/" . $class_name . ".view.php"))
                require_once __DIR__ . "/view/" . $class_name . ".view.php";
        });
        $this->config = include_once(__DIR__ . "/config.php");
        $this->connection = (new Database($this->config))->connect();
    }

    public function dispatch()
    {
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        // var_dump(explode('/', $path));
        if ($path === "/api/")
            $controller_name = "Index";
        else
            $controller_name = ucfirst(explode('/', $path)[count(explode('/', $path)) - 1]);
        // echo $controller_name;
        if (!is_file(__DIR__ . "/controller/" . $controller_name . ".class.php"))
            $controller_name = "NotFound";
        $controller = new $controller_name($this->connection);
        /**
         * @var Controller $controller
         */
        $controller->json();
    }

    public function run()
    {
        (new Users($this->connection))->createTable();
        (new Boards($this->connection))->createTable();
        (new Threads($this->connection))->createTable();
        (new Replies($this->connection))->createTable();
        (new Favorites($this->connection))->createTable();
        (new Messages($this->connection))->createTable();
        $this->dispatch();
    }

}
