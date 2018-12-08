<?php
require_once __DIR__ . "/../controller/Controller.class.php";
require_once __DIR__ . "/../model/Messages.class.php";

class Send extends Controller
{
    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->model = new Messages($connection);
    }

    public function send()
    {
        if(isset($_POST["to"])&&isset($_POST["content"]))
        {
            $message = array ("message_from" => $_SESSION["user_id"], "message_to" =>$_POST["to"], "message_content" => ["content"]);
            return $this->model->seedMessage($message);
        }
        else
            return NULL;
    }

    public function format()
    {
        return;
    }

}