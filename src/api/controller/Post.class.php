<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Threads.class.php";

class Post extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Threads($connection);
        $this->array = array("board" => (new Boards($this->connection))->selectAll($_GET["board_id"])["info"]);
    }

    public function post()
    {
        if (isset($_POST["thread_title"]) && isset($_POST["thread_content"])) {
            $thread["user_id"] = $_SESSION["user_id"];
            $thread["board_id"] = $_GET["board_id"];
            $thread["thread_title"] = pg_escape_string($_POST["thread_title"]);
            $thread["thread_content"] = pg_escape_string($_POST["thread_content"]);
            $thread["thread_time"] = date("Y-m-d h:i:s");
            return ($this->model->post($thread)) ? true : false;
        } else {
            return false;
        }
    }

    public function json()
    {
        if (isset($_POST["thread_title"]) && isset($_POST["thread_content"]) && isset($_SESSION["user_id"]))
            if ($this->post())
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