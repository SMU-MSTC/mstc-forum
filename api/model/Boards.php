<?php
require_once __DIR__ . "/../model/Model.php";

class Boards extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS boards (
                                   board_id serial NOT NULL PRIMARY KEY,
                                   board_name varchar(32) NOT NULL UNIQUE,
                                   board_intro text
                                 );";

    public function create($board_name, $board_intro)
    {
        if (pg_num_rows(pg_query($this->connection, "SELECT * FROM boards WHERE board_name='$board_name'")) !== 0)
            return false;
        else {
            $create_query = "INSERT INTO boards (board_name, board_intro) VALUES ('$board_name', '$board_intro')";
            return pg_query($this->connection, $create_query) ? true : false;
        }
    }

    public function update($board_id, $board_name, $board_intro)
    {
        if (pg_num_rows(pg_query($this->connection, "SELECT * FROM boards WHERE board_name='$board_name'")) !== 0)
            return false;
        else {
            $update_query = "UPDATE boards SET board_name='$board_name', board_intro='$board_intro'
                             WHERE board_id='$board_id'";
            return pg_query($this->connection, $update_query) ? true : false;
        }
    }

    public function selectAll($board_id)
    {
        if (isset($board_id)) {
            $threads = pg_fetch_all(pg_query($this->connection, "SELECT threads.*, users.user_name FROM threads
                                                                         LEFT JOIN users ON threads.user_id=users.user_id
                                                                       WHERE board_id='$board_id' ORDER BY thread_time DESC"));
            $info = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM boards WHERE board_id='$board_id'"));
            if ($info)
                return array("info" => $info, "threads" => $threads);
            else
                return null;
        } else
            return parent::selectAll(null);
    }

}
