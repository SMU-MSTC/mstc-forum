<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Users.class.php";

class Register extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Users($connection);
    }

    public function register()
    {
        if (isset($_POST["user_name"]) && isset($_POST["user_name"]) && isset($_POST["user_email"]) && isset($_POST["user_tel"])) {
            $register["user_name"] = pg_escape_string(trim($_POST["user_name"]));
            $register["user_password"] = pg_escape_string(trim($_POST["user_password"]));
            $register["user_email"] = pg_escape_string(trim($_POST["user_email"]));
            $register["user_tel"] = pg_escape_string(trim($_POST["user_tel"]));
            return ($this->model->register($register)) ? true : false;
        } else
            return false;
    }

    public function json()
    {
        if (isset($_POST["user_name"]) && isset($_POST["user_password"]) && isset($_POST["user_email"]) && isset($_POST["user_tel"]))
            if ($this->register())
                echo '1';
            else
                echo '0';
        else
            parent::json();
    }

    public function format()
    {
        return;
    }

}
