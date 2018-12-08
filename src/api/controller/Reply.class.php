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
        if (isset($_POST["reply_id"]))
        {
            $reply = array("thread_id" => $_POST["thread_id"], "reply_id" => $_POST["reply_id"], "reply_content" => $_POST["reply_content"]);
            $message = array("message_reply_id" => $_POST["reply_id"], "message_from" => $_POST["thread_id"]);
            return $this->model->reply($reply)&&(new Messages($this->connection))->replyMessage($message);
        }
        else
        {
            $reply = array("thread_id" => $_POST["thread_id"], "reply_content" => $_POST["reply_content"]);
            return $this->model->reply($reply);
        }
    }

    public function json()
    {
        if (isset($_POST["thread_id"]) && isset($_POST["reply_content"]) && $_POST["reply_content"] != "" && isset($_SESSION["user_id"]))
            if ($this->reply())
                echo '1';
            else
                echo '0';
    }

    public function format()
    {
        return;
    }

}