<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Messages.class.php";

class Message extends Controller {

    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Messages($connection);
        $this->array["message"] = $this->model->selectAll($_SESSION["user_id"]);
        $this->format();
    }

    public function format()
    {
        foreach ($this->array["message"] as &$message)
        {
            $message["message_id"] = (int)$message["message_id"];
            $message["message_from"] = (int)$message["message_from"];
            $message["message_to"] = (int)$message["message_to"];
            $message["message_time"] = date("Y-m-d h:i:s", strtotime($message["message_time"]));
            if ($message["message_type"] === "t")
                $message["message_type"] = true;
            elseif ($message["message_type"] === "f")
                $message["message_type"] = false;
            if ($message["message_is_read"] === "t")
                $message["message_is_read"] = true;
            elseif ($message["message_is_read"] === "f")
                $message["message_is_read"] = false;
            if ($message["message_type"] === false)
                $message["message_from_name"] = (new Users($this->connection))->getUserName($message["message_from"])["user_name"];
            else
                $message["message_from_name"] = NULL;
//        $this->array["message"]["message_num"] = count($this->array["message"]);
            $this->model->readMark($message["message_id"]);
        }
    }


}