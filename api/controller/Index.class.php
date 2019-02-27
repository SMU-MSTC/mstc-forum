<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Boards.class.php";

class Index extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Boards($connection);
        $this->array["boards"] = $this->model->selectAll(null);
        $this->format();
    }

    public function format()
    {
        foreach ($this->array["boards"] as &$board)
            $board["board_id"] = (int)$board["board_id"];
    }

}
