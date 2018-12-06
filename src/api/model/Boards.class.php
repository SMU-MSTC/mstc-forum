<?php
require_once __DIR__ . "/../model/Model.class.php";

class Boards extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS boards (
                                    board_id serial NOT NULL PRIMARY KEY,
                                    board_name varchar(32) NOT NULL UNIQUE,
                                    board_intro text
                                );";

    public function selectAll($board_id)
    {
        if (isset($board_id)) {
            $threads = pg_fetch_all(pg_query($this->connection, "SELECT * FROM threads WHERE board_id='$board_id' ORDER BY thread_time DESC"));
            $info = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM boards WHERE board_id='$board_id'"));
            return array("info" => $info, "threads" => $threads);
        } else
            return parent::selectAll(NULL);
    }

}
