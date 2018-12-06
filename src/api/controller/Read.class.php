<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Threads.class.php";

class Read extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Threads($connection);
        $this->array = array_merge($this->array, $this->model->selectAll($_GET["thread_id"]));
        $this->format();
    }

    public function format()
    {
        $this->array["thread"]["thread_id"] = (int)$this->array["thread"]["thread_id"];
        $this->array["thread"]["user_id"] = (int)$this->array["thread"]["user_id"];
        $this->array["thread"]["user_name"] = (new Users($this->connection))->selectAll($this->array["thread"]["user_id"])["user_name"];
        $this->array["thread"]["board_id"] = (int)$this->array["thread"]["board_id"];
        $this->array["thread"]["board_name"] = (new Boards($this->connection))->selectAll($this->array["thread"]["board_id"])["info"]["board_name"];
        $this->array["thread"]["thread_time"] = date("Y-m-d h:i:s", strtotime($this->array["thread"]["thread_time"]));
        if ($this->array["thread"]["thread_visible"] === "f")
            $this->array["thread"]["thread_visible"] = false;
        elseif ($this->array["thread"]["thread_visible"])
            $this->array["thread"]["thread_visible"] = true;
        foreach ($this->array["replies"] as $key => &$reply) {
            $reply["reply_id"] = (int)$reply["reply_id"];
            $reply["user_id"] = (int)$reply["user_id"];
            $reply["user_name"] = (new Users($this->connection))->selectAll($reply["user_id"])["user_name"];
            $reply["thread_id"] = (int)$reply["thread_id"];
            $reply["reply_time"] = date("Y-m-d h:i:s", strtotime($reply["reply_time"]));
            if ($reply["reply_visible"] === "f")
                $reply["reply_visible"] = false;
            elseif ($reply["reply_visible"] === "t")
                $reply["reply_visible"] = true;
            if ($reply["reply_is_reply"] === "f")
                $reply["reply_is_reply"] = false;
            elseif ($reply["reply_is_reply"] === "t")
                $reply["reply_is_reply"] = true;
            if ($reply["reply_is_reply"]) {
                $reply["reply_reply_id"] = (int)$reply["reply_reply_id"];
                $reply_reply = (new Replies($this->connection))->selectAll($reply["reply_reply_id"]);
                $reply["reply_reply_content"] = $reply_reply["reply_content"];
                $reply["reply_reply_user_id"] = (int)$reply_reply["user_id"];
                $reply["reply_reply_user_name"] = (new Users($this->connection))->selectAll($reply_reply["user_id"])["user_name"];
                $reply["reply_reply_time"] = date("Y-m-d h:i:s", strtotime($reply_reply["reply_time"]));
            }
            if ($reply["reply_visible"] === false)
                unset($this->array["replies"][$key]);
        }
        if ($this->array["replies"] === false)
            unset($this->array["replies"]);
        $this->array["replies"] = array_values($this->array["replies"]);
    }

}