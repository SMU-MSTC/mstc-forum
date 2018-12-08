<?php
require_once __DIR__ . "/../model/Model.class.php";

class Replies extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS replies (
                                    reply_id serial NOT NULL PRIMARY KEY,
                                    user_id serial NOT NULL REFERENCES users,
                                    thread_id serial NOT NULL REFERENCES threads,
                                    reply_content text NOT NULL,
                                    reply_time timestamp NOT NULL,
                                    reply_visible boolean NOT NULL DEFAULT TRUE,
                                    reply_is_reply boolean NOT NULL DEFAULT FALSE,
                                    reply_reply_id integer REFERENCES replies (reply_id) DEFAULT NULL
                                );";

    public function reply($reply)
    {
        if (isset($reply)) {
            /**
             * @var int $thread_id
             * @var int $reply_id
             * @var string $reply_content
             */
            extract($reply);
            $user_id = $_SESSION["user_id"];
            $reply_time = date("Y-m-d h:i:s");
            $reply_content = pg_escape_string($reply_content);
            if (isset($reply_id)) {
                $reply_query = "INSERT INTO replies (user_id, thread_id, reply_content, reply_time, reply_is_reply, reply_reply_id)
                       VALUES ('$user_id', '$thread_id', '$reply_content', '$reply_time', TRUE, '$reply_id')";
            } else {
                $reply_query = "INSERT INTO replies (user_id, thread_id, reply_content, reply_time)
                       VALUES ('$user_id', '$thread_id', '$reply_content', '$reply_time')";
            }
            return pg_query($this->connection, $reply_query) ? true : false;
        } else
            return false;
    }

    public function delete($reply_id)
    {
        $delete_query = "UPDATE replies SET reply_visible = FALSE WHERE reply_id = '$reply_id'";
        return pg_query($this->connection, $delete_query) ? true : false;
    }

    public function selectAll($reply_id)
    {
        if (isset($reply_id)) {
            return pg_fetch_assoc(pg_query($this->connection, "SELECT * FROM replies WHERE reply_id='$reply_id'"));
        } else
            return NULL;
    }

    public function getUserId($reply_id)
    {
        $reply_query = "SELECT user_id FROM replies WHERE reply_id='$reply_id'";
        $result = pg_query($this->connection, $reply_query);
        $result = pg_fetch_assoc($result)["user_id"];
        return $result;
    }

}
