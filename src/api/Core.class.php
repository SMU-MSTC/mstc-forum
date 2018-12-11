<?php

class Core
{

    public $config, $connection, $initialized;

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

    public function init()
    {
        $tables = array("Users", "Boards", "Threads", "Replies", "Messages", "Favorites");
        foreach ($tables as &$table_name) {
            $result = pg_fetch_assoc(pg_query($this->connection, "SELECT TO_REGCLASS('$table_name')"))["to_regclass"];
            if (!$result) {
                $model = new $table_name($this->connection);
                /**
                 * @var Model $model
                 */
                $model->createTable();
            }
        }
    }

    public function dispatch()
    {
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        if ($path === "/api/")
            $controller_name = "Index";
        else
            $controller_name = ucfirst(explode('/', $path)[count(explode('/', $path)) - 1]);
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

        $this->init();
        $this->dispatch();

    }

}
