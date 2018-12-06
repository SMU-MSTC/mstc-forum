<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Boards.class.php";

class Board extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Boards($connection);
        $this->array["board"] = $this->model->selectAll($_GET["board_id"]);
        $this->format();
    }

    public function format()
    {
        $this->array["board"]["info"]["board_id"] = (int)$this->array["board"]["info"]["board_id"];
        foreach ($this->array["board"]["threads"] as $key => &$thread) {
            $thread["thread_id"] = (int)$thread["thread_id"];
            $thread["user_id"] = (int)$thread["user_id"];
            $thread["user_name"] = (new Users($this->connection))->selectAll($thread["user_id"])["user_name"];
            $thread["board_id"] = (int)$thread["board_id"];
            $thread["thread_time"] = date("Y-m-d h:i:s", strtotime($thread["thread_time"]));
            if ($thread["thread_visible"] === "f")
                $thread["thread_visible"] = false;
            elseif ($thread["thread_visible"] === "t")
                $thread["thread_visible"] = true;
            if ($thread["thread_visible"] === false)
                unset($this->array["board"]["threads"][$key]);
            $this->array["board"]["threads"] = array_values($this->array["board"]["threads"]);
        }
    }

}
