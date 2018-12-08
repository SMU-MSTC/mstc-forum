<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Favorites.class.php";

class Favorite extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Favorites($connection);
        $this->array["favorites"] = $this->model->selectAll($_SESSION["user_id"]);
        $this->format();
    }

    public function favorite()
    {
        if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== null && isset($_POST["thread_id"]) && $_POST["thread_id"] !== null) {
            return ($this->model->favorite($_SESSION["user_id"], $_POST["thread_id"])) ? true : false;
        } else
            return false;
    }

    public function json()
    {
        if (isset($_POST["thread_id"]) && isset($_POST["thread_id"]))
            if ($this->favorite())
                echo '1';
            else
                echo '0';
        else
            parent::json();
    }


    public function format()
    {
        if ($this->array["favorites"]) {
            foreach ($this->array["favorites"] as $key => &$favorite) {
                $favorite["thread_id"] = (int)$favorite["thread_id"];
                $favorite["user_id"] = (int)$favorite["user_id"];
                $favorite["user_name"] =  (new Users($this->connection))->selectAll($favorite["user_id"])["user_name"];
                $favorite["board_id"] = (int)(new Threads($this->connection))->selectAll($favorite["thread_id"])["thread"]["board_id"];
                $favorite["board_name"] = (new Boards($this->connection))->selectAll($favorite["board_id"])["info"]["board_name"];
                $favorite["thread_title"] = (new Threads($this->connection))->selectAll($favorite["thread_id"])["thread"]["thread_title"];
                $favorite["thread_content"] = (new Threads($this->connection))->selectAll($favorite["thread_id"])["thread"]["thread_content"];
                $favorite["thread_time"] = date("Y-m-d h:i:s", strtotime((new Threads($this->connection))->selectAll($favorite["thread_id"])["thread"]["thread_time"]));
                $favorite["thread_visible"] = (new Threads($this->connection))->selectAll($favorite["thread_id"])["thread"]["thread_visible"];
                if ($favorite["thread_visible"] === "f")
                    $favorite["thread_visible"] = false;
                elseif ($favorite["thread_visible"] === "t")
                    $favorite["thread_visible"] = true;
                if (!$favorite["thread_visible"])
                    unset($this->array["favorites"][$key]);
                $favorite["favorite"] = (new Favorites($this->connection))->isFavorite($_SESSION["user_id"], $favorite["thread_id"]);
                $this->array["favorites"] = array_values($this->array["favorites"]);
            }
        }
    }

}