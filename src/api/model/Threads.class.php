<?php
require_once __DIR__ . "/../model/Model.class.php";

class Threads extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS threads (
                                    thread_id serial NOT NULL PRIMARY KEY,
                                    user_id serial NOT NULL REFERENCES users,
                                    board_id serial NOT NULL REFERENCES boards,
                                    thread_title text NOT NULL,
                                    thread_content text NOT NULL,
                                    thread_time timestamp NOT NULL,
                                    thread_visible boolean NOT NULL DEFAULT TRUE
                                );";

    public function post($thread)
    {
        extract($thread);
        /**
         * @var int $user_id
         * @var int $board_id
         * @var string $thread_title
         * @var string $thread_content
         * @var string $thread_time
         */
        $post_query = "INSERT INTO threads (user_id, board_id, thread_title, thread_content, thread_time)
                       VALUES ('$user_id', '$board_id', '$thread_title', '$thread_content', '$thread_time');";
        return pg_query($this->connection, $post_query) ? true : false;
    }

    public function delete($thread_id)
    {
        $delete_query = "UPDATE threads SET thread_visible = FALSE WHERE thread_id = '$thread_id'";
        return pg_query($this->connection, $delete_query) ? true : false;
    }

    public function selectAll($thread_id)
    {
        if (isset($thread_id)) {
            $thread = pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM threads WHERE thread_id='$thread_id'"));
            $replies = pg_fetch_all(pg_query($this->connection, "SELECT * FROM replies WHERE thread_id='$thread_id'"));
            return array("thread" => $thread, "replies" => $replies);
        } else
            return NULL;
    }

}
