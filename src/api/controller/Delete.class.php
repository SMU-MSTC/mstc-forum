<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Threads.class.php";
require_once __DIR__ . "/../model/Replies.class.php";

class Delete extends Controller
{
    public function __construct($connection)
    {
        parent::__construct($connection);
    }

    public function delete()
    {
        if (isset($_POST["thread_id"]) || isset($_POST["reply_id"]))
            if (isset($_POST["thread_id"]))
                return (new Threads($this->connection))->delete(pg_escape_string($_POST["thread_id"])) ? true : false;
            else
                return (new Replies($this->connection))->delete(pg_escape_string($_POST["reply_id"])) ? true : false;
        else
            return false;
    }

    public function json()
    {
        if ((isset($_POST["thread_id"]) || isset($_POST["reply_id"])) && isset($_SESSION["user_id"]) && $_SESSION["user_name"] === "admin")
            if ($this->delete())
                echo '1';
            else
                echo '0';
        else
            echo '0';
    }

    public function format()
    {
        return;
    }

}