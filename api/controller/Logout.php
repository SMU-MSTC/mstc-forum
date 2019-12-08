<?php
require_once __DIR__ . "/../controller/Controller.php";
require_once __DIR__ . "/../model/Users.php";

class Logout extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
    }

    public function logout()
    {
        if (isset($_SESSION["user_name"])) {
            unset($_SESSION["user_id"]);
            unset($_SESSION["user_name"]);
            unset($_SESSION["user_is_admin"]);
        }
    }

    public function json()
    {
        $this->logout();
        $this->set();
        parent::json();
    }

    public function format()
    {
        return;
    }

}
