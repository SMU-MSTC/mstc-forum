<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Users.class.php";

class Login extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Users($connection);
    }

    public function login()
    {
        if (isset($_POST["user_name"]) && isset($_POST["user_password"])) {
            $login["user_name"] = pg_escape_string($_POST["user_name"]);
            $login["user_password"] = pg_escape_string($_POST["user_password"]);
            return ($this->model->login($login)) ? true : false;
        } else
            return false;
    }

    public function json()
    {
        if (isset($_POST["user_name"]) && isset($_POST["user_password"]))
            if ($this->login())
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
