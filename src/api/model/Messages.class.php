<?php
require_once __DIR__ . "/../model/Model.class.php";

class Messages extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS messages (
                                   message_id serial NOT NULL PRIMARY KEY,
                                   message_from serial REFERENCES users (user_id),
                                   message_to serial NOT NULL REFERENCES users (user_id),
                                   message_content text,
                                   message_time timestamp,
                                   message_type boolean NOT NULL,
                                   message_is_read boolean NOT NULL DEFAULT FALSE
                                 );";

    public function reply($message)
    {
        /**
         * @var string $message_reply_id
         * @var string $message_thread_id
         * @var string $message_from
         * @var string $message_content
         * @var string $message_time
         */
        extract($message);
        if (isset($message_reply_id)) {
            $message_to = (int)(new Replies($this->connection))->selectAll($message_reply_id)["user_id"];
            $message_query = "INSERT INTO messages (message_from, message_to, message_content, message_time, message_type) 
                              VALUES('$message_from', '$message_to', '$message_content', '$message_time', TRUE)";
            return pg_query($this->connection, $message_query) ? true : false;
        } else if (isset($message_thread_id)) {
            $message_to = (int)(new Threads($this->connection))->selectAll($message_thread_id)["thread"]["user_id"];
            $message_query = "INSERT INTO messages (message_from, message_to, message_content, message_time, message_type) 
                              VALUES('$message_from', '$message_to', '$message_content', '$message_time', TRUE)";
            return pg_query($this->connection, $message_query) ? true : false;
        } else
            return false;
    }

    public function send($message)
    {
        /**
         * @var string $message_from
         * @var string $message_to
         * @var string $message_content
         * @var string $message_time
         */
        extract($message);
        $message_query = "INSERT INTO messages (message_from, message_to, message_content, message_time, message_type) 
                          VALUES('$message_from', '$message_to', '$message_content', '$message_time', FALSE)";
        return pg_query($this->connection, $message_query) ? true : false;
    }

    public function read($message_id)
    {
        $message_query = "UPDATE messages SET message_is_read = true WHERE message_id='$message_id'";
        return pg_query($this->connection, $message_query) ? true : false;
    }

    public function select($message_from, $message_content, $message_time)
    {
        $select_query = "SELECT * FROM replies WHERE user_id='$message_from' AND reply_content='$message_content' AND reply_time='$message_time'";
        return pg_fetch_assoc(pg_query($this->connection, $select_query));
    }

    public function selectAll($user_id)
    {
        if (isset($user_id)) {
            $notice = pg_fetch_all(pg_query($this->connection, "SELECT messages.*, users.user_name AS message_from_user_name,
                                                                          users.user_is_admin AS message_from_user_is_admin FROM messages
                                                                        LEFT JOIN users ON users.user_id=message_from
                                                                      WHERE message_to='$user_id' AND message_type=FALSE ORDER BY message_id DESC"));
            $message = pg_fetch_all(pg_query($this->connection, "SELECT messages.*, users.user_name AS message_from_user_name,
                                                                           replies.thread_id AS message_from_thread_id,
                                                                           threads.thread_title AS message_from_thread_title FROM messages
                                                                         LEFT JOIN users ON users.user_id=message_from
                                                                         LEFT JOIN replies ON replies.user_id=message_from
                                                                           AND replies.reply_content=message_content AND replies.reply_time=message_time
                                                                         LEFT JOIN threads ON threads.thread_id=replies.thread_id
                                                                       WHERE message_to='$user_id' AND message_type=TRUE ORDER BY message_id DESC"));
            $result = array_merge($notice, $message);
            foreach ($result as $key => $value)
                $message_id[$key] = $value["message_id"];
            array_multisort($message_id, SORT_DESC,$result);
            return $result;
        } else {
            return NULL;
        }
    }

}
