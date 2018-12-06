<?php

abstract class Controller
{

    protected $controller;
    protected $model;
    protected $connection;
    protected $array;

    public function __construct($connection)
    {
        $this->controller = lcfirst(get_class($this));
        $this->connection = $connection;
        $this->set();
    }

    public function json()
    {
        header("Content-type:application/json;charset=utf-8");
        echo json_encode($this->array, JSON_PRETTY_PRINT);
    }

    public function set()
    {
        session_start();
        $this->array["session"]["user_id"] = isset($_SESSION["user_id"]) ? (int)$_SESSION["user_id"] : null;
        $this->array["session"]["user_name"] = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : null;
        if (isset($_SESSION["user_is_admin"])) {
            if ($_SESSION["user_is_admin"] === "f")
                $this->array["session"]["user_is_admin"] = false;
            elseif ($_SESSION["user_is_admin"] === "t")
                $this->array["session"]["user_is_admin"] = true;
            else
                $this->array["session"]["user_is_admin"] = null;
        } else
            $this->array["session"]["user_is_admin"] = null;
    }

    abstract public function format();

}
