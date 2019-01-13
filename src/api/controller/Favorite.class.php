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
        if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] && isset($_POST["thread_id"]) && $_POST["thread_id"]) {
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
                $favorite["board_id"] = (int)$favorite["board_id"];
                $favorite["thread_time"] = date("Y-m-d h:i:s", strtotime($favorite["thread_time"]));
                if ($favorite["thread_visible"] === "f")
                    $favorite["thread_visible"] = false;
                elseif ($favorite["thread_visible"] === "t")
                    $favorite["thread_visible"] = true;
                if (!$favorite["thread_visible"])
                    unset($this->array["favorites"][$key]);
                $favorite["favorite"] = (new Favorites($this->connection))->isFavorite($_SESSION["user_id"], $favorite["thread_id"]);
                $this->array["favorites"] = array_values($this->array["favorites"]);
                if ($this->array["favorites"] === [])
                    $this->array["favorites"] = null;
            }
        }
    }

}