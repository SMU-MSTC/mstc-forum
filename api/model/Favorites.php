<?php
require_once __DIR__ . "/../model/Model.php";

class Favorites extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS favorites (
                                   user_id serial NOT NULL REFERENCES users,
                                   thread_id serial NOT NULL REFERENCES threads,
                                   PRIMARY KEY (user_id, thread_id)
                                 );";

    public function favorite($user_id, $thread_id)
    {
        if (isset($user_id) && isset($thread_id)) {
            if (pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM favorites WHERE user_id='$user_id' AND thread_id='$thread_id'")))
                return pg_query($this->connection, "DELETE FROM favorites WHERE user_id='$user_id' AND thread_id='$thread_id'") ? true : false;
            else
                return pg_query($this->connection, "INSERT INTO favorites VALUES ('$user_id', '$thread_id')") ? true : false;
        } else
            return false;
    }

    public function isFavorite($user_id, $thread_id)
    {
        if (isset($user_id) && isset($thread_id)) {
            return pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM favorites WHERE user_id='$user_id' AND thread_id='$thread_id'")) ? true : false;
        }
    }

    public function selectAll($user_id)
    {
        if (isset($user_id) && ($user_id)) {
            $result = pg_fetch_all(pg_query($this->connection, "SELECT favorites.*, threads.*, boards.board_name, users.user_name FROM favorites
                                                                        LEFT JOIN threads ON threads.thread_id=favorites.thread_id
                                                                        LEFT JOIN boards ON boards.board_id=threads.thread_id
                                                                        LEFT JOIN users ON users.user_id=threads.user_id
                                                                      WHERE favorites.user_id='$user_id'"));
            return $result ? $result : null;
        } else
            return null;
    }

}
