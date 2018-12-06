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

}
