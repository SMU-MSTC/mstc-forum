<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Users.class.php";

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
        parent::json();
        $this->logout();
    }

    public function format()
    {
        return;
    }

}
