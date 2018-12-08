<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Users.class.php";

class User extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Users($connection);
        $this->array["user"] = $this->model->selectAll($_GET["user_id"]);
        $this->format();
    }

    public function json()
    {
        if (isset($_POST["user_name"]) && isset($_POST["user_password"]))
            if ($this->update())
                echo '1';
            else
                echo '0';
        else
            parent::json();
    }

    public function update()
    {
        if (isset($_POST["user_name"]) && isset($_POST["user_password"])) {
            if ($_GET["user_id"] === $_SESSION["user_id"]) {
                $update["user_name"] = pg_escape_string($_POST["user_name"]);
                $update["user_password"] = pg_escape_string($_POST["user_password"]);
                $update["new_password"] = pg_escape_string($_POST["new_password"]);
                $update["user_gender"] = pg_escape_string($_POST["user_gender"]);
                $update["user_birth"] = pg_escape_string($_POST["user_birth"]);
                $update["user_email"] = pg_escape_string($_POST["user_email"]);
                $update["user_tel"] = pg_escape_string($_POST["user_tel"]);
                $update["user_intro"] = pg_escape_string($_POST["user_intro"]);
                return ($this->model->update($update)) ? true : false;
            } else {
                return false;
            }
        } else
            return false;
    }

    public function format()
    {
        $this->array["user"]["user_id"] = (int)$this->array["user"]["user_id"];
        unset($this->array["user"]["user_password"]);
        if ($this->array["user"]["user_is_admin"] === "f")
            $this->array["user"]["user_is_admin"] = false;
        elseif ($this->array["user"]["user_is_admin"] === "t")
            $this->array["user"]["user_is_admin"] = true;
    }

}