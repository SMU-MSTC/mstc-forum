<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Messages.class.php";

class Message extends Controller {

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Messages($connection);
    }

    public function format()
    {
        return;
    }


}