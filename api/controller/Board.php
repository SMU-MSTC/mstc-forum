<?php
require_once __DIR__ . "/../controller/Controller.php";
require_once __DIR__ . "/../model/Boards.php";

class Board extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Boards($connection);
        $this->array["board"] = $this->model->selectAll($_GET["board_id"]);
        $this->format();
    }

    public function create()
    {
        if ($_SESSION["user_is_admin"] && isset($_POST["board_name"]) && isset($_POST["board_intro"])) {
            $board_name = pg_escape_string(trim($_POST["board_name"]));
            $board_intro = pg_escape_string(trim($_POST["board_intro"]));
            return $this->model->create($board_name, $board_intro) ? true : false;
        } else
            return false;
    }

    public function update()
    {
        if ($_SESSION["user_is_admin"] && isset($_GET["board_id"]) && isset($_POST["board_name"]) && isset($_POST["board_intro"])) {
            $board_name = pg_escape_string(trim($_POST["board_name"]));
            $board_intro = pg_escape_string(trim($_POST["board_intro"]));
            return $this->model->update($_GET["board_id"], $board_name, $board_intro) ? true : false;
        } else
            return false;
    }

    public function json()
    {
        if ($_SESSION["user_is_admin"] && isset($_GET["board_id"]) && isset($_POST["board_name"]) && isset($_POST["board_intro"]))
            if ($this->update())
                echo '1';
            else
                echo '0';
        else if ($_SESSION["user_is_admin"] && isset($_POST["board_name"]) && isset($_POST["board_intro"]))
            if ($this->create())
                echo '1';
            else
                echo '0';
        else
            parent::json();
    }

    public function format()
    {
        if ($this->array["board"]) {
            $this->array["board"]["info"]["board_id"] = (int)$this->array["board"]["info"]["board_id"];
            foreach ($this->array["board"]["threads"] as $key => &$thread) {
                $thread["thread_id"] = (int)$thread["thread_id"];
                $thread["user_id"] = (int)$thread["user_id"];
                $thread["board_id"] = (int)$thread["board_id"];
                $thread["thread_time"] = date("Y-m-d h:i:s", strtotime($thread["thread_time"]));
                if ($thread["thread_visible"] === "f")
                    $thread["thread_visible"] = false;
                elseif ($thread["thread_visible"] === "t")
                    $thread["thread_visible"] = true;
                if (!$thread["thread_visible"])
                    unset($this->array["board"]["threads"][$key]);
                if (isset($_SESSION["user_id"]) && $_SESSION["user_id"])
                    $thread["favorite"] = (new Favorites($this->connection))->isFavorite($_SESSION["user_id"], $thread["thread_id"]);
                $this->array["board"]["threads"] = array_values($this->array["board"]["threads"]);
            }
        }
    }

}
