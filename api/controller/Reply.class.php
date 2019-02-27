<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Users.class.php";

class Reply extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Replies($connection);
    }

    public function reply()
    {
        $reply_content = pg_escape_string(trim($_POST["reply_content"]));
        $reply_time = date("Y-m-d h:i:s");
        if (isset($_POST["reply_id"])) {
            $reply = array("user_id" => (int)$_SESSION["user_id"], "thread_id" => (int)$_POST["thread_id"], "reply_id" => (int)$_POST["reply_id"], "reply_content" => $reply_content, "reply_time" => $reply_time);
            $message = array("message_reply_id" => (int)$_POST["reply_id"], "message_from" => (int)$_SESSION["user_id"], "message_content" => $reply_content, "message_time" => $reply_time);
            return $this->model->reply($reply) && (new Messages($this->connection))->reply($message);
        } else {
            $reply_content = pg_escape_string(trim($_POST["reply_content"]));
            $reply = array("user_id" => (int)$_SESSION["user_id"], "thread_id" => (int)$_POST["thread_id"], "reply_content" => $reply_content, "reply_time" => $reply_time);
            $message = array("message_thread_id" => (int)$_POST["thread_id"], "message_from" => (int)$_SESSION["user_id"], "message_content" => $reply_content, "message_time" => $reply_time);
            return $this->model->reply($reply) && (new Messages($this->connection))->reply($message);
        }
    }

    public function json()
    {
        if (isset($_POST["thread_id"]) && isset($_POST["reply_content"]) && $_POST["reply_content"] !== "" && isset($_SESSION["user_id"]))
            if ($this->reply())
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
