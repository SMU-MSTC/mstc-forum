<?php
require_once __DIR__ . "/../controller/Controller.class.php";

class NotFound extends Controller
{

    public function __construct($connection)
    {
        parent::__construct($connection);
        http_response_code(404);
    }

    public function json()
    {
        http_response_code(404);
    }

    public function format()
    {
        return;
    }

}