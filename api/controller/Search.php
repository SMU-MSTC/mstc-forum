<?php
require_once __DIR__ . "/../controller/Controller.php";
require_once __DIR__ . "/../model/Threads.php";
require_once __DIR__ . "/../model/Replies.php";
require_once __DIR__ . "/../controller/Read.php";

class Search extends Controller
{
    public function __construct($connection)
    {
        parent::__construct($connection);
    }

    public function search()
    {
        if (isset($_POST["search"])) {
            $search = pg_escape_string(trim($_POST["search"]));
            $search_thread = (new Threads($this->connection))->search($search);
            $search_reply = (new Replies($this->connection))->search($search);
            $this->array = array_merge($this->array, array("threads" => $search_thread, "replies" => $search_reply));
        } else {
            return null;
        }
    }

    public function json()
    {
        $this->search();
        $this->format();
        parent::json();
    }

    public function format()
    {
        foreach ($this->array["threads"] as $key => &$thread) {
            $thread["thread_id"] = (int)$thread["thread_id"];
            $thread["user_id"] = (int)$thread["user_id"];
            $thread["user_name"] = (new Users($this->connection))->selectAll($thread["user_id"])["user_name"];
            $thread["board_id"] = (int)$thread["board_id"];
            $thread["board_name"] = (new Boards($this->connection))->selectAll($thread["board_id"])["info"]["board_name"];
            $thread["thread_time"] = date("Y-m-d h:i:s", strtotime($thread["thread_time"]));
            if (isset($_SESSION["user_id"]) && $_SESSION["user_id"])
                $thread["favorite"] = (new Favorites($this->connection))->isFavorite($_SESSION["user_id"], $thread["thread_id"]);
            if ($thread["thread_visible"] === "f")
                $thread["thread_visible"] = false;
            elseif ($thread["thread_visible"])
                $thread["thread_visible"] = true;
            if (!$thread["thread_visible"]) {
                unset($this->array["threads"][$key]);
            }
        }
        if (!$this->array["threads"])
            unset($this->array["threads"]);
        $this->array["threads"] = array_values($this->array["threads"]);
        foreach ($this->array["replies"] as $key => &$reply) {
            $reply["reply_id"] = (int)$reply["reply_id"];
            $reply["user_id"] = (int)$reply["user_id"];
            $reply["user_name"] = (new Users($this->connection))->selectAll($reply["user_id"])["user_name"];
            $reply["thread_id"] = (int)$reply["thread_id"];
            $reply["thread_title"] = (new Threads($this->connection))->selectAll($reply["thread_id"])["thread"]["thread_title"];
            $reply["board_id"] = (new Threads($this->connection))->selectAll($reply["thread_id"])["thread"]["board_id"];
            $reply["board_name"] = (new Boards($this->connection))->selectAll($reply["board_id"])["info"]["board_name"];
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
                if ($reply_reply["reply_visible"] === "f") {
                    $reply["reply_reply_content"] = null;
                    unset($reply["reply_reply_user_id"]);
                    unset($reply["reply_reply_user_name"]);
                    unset($reply["reply_reply_time"]);
                }
            }
            if (!$reply["reply_visible"])
                unset($this->array["replies"][$key]);
        }
        if (!$this->array["replies"])
            unset($this->array["replies"]);
        $this->array["replies"] = array_values($this->array["replies"]);
    }

}