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
    public function replyMessage($message)
    {
        extract($message);
        /**
         * @var string $message_from
         * @var string $message_reply_id;
         * @var string $message_thread_id;
         * @var string $message_to
         * @var string $message_content
         * @var string $message_time
         */
        if(isset($message_reply_id))
        {
            $message_to = (new Replies($this->connection))->getUserId($message_reply_id);
            unset($message_reply_id);
        }

        $message_content = "Someone Replied You @ Here.";
        $message_to = (int)$message_to;
        $message_from = (int)$message_from;
        $message_time = date("Y-m-d h:i:s");
        $message_query = "INSERT INTO messages (message_from, message_to, message_content, message_time, message_type, message_is_read) 
                        VALUES('$message_from', '$message_to', '$message_content', '$message_time', TRUE, FALSE)";
        return pg_query($this->connection, $message_query) ? true : false;
    }

    public function seedMessage($message)
    {
        extract($message);
        /**
         * @var string $message_from
         * @var string $message_to
         * @var string $message_content
         * @var string $message_time
         */
        $message_from = (int)$message_from;
        $message_to = (int)$message_to;
        $message_time = date("Y-m-d h:i:s");
        $message_content = pg_escape_string($message_content);
        $message_query = "INSERT INTO messages (message_from, message_to, message_content, message_time, message_type, message_is_read) 
                        VALUES('$message_from', '$message_to', '$message_content', '$message_time', FALSE, FALSE)";
        return pg_query($this->connection, $message_query) ? true : false;
    }

    public function selectAll($user_id)
    {
        if(isset($user_id))
        {
            $message_query = "SELECT * FROM messages WHERE message_to='$user_id'";
            $result = pg_query($this->connection, $message_query);
            return pg_fetch_all($result);
        }
        else
        {
            return NULL;
        }
    }

}
