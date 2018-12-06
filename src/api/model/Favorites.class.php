<?php
require_once __DIR__ . "/../model/Model.class.php";

class Favorites extends Model
{

    protected $create_string = "CREATE TABLE IF NOT EXISTS favorites (
                                    user_id serial NOT NULL REFERENCES users,
                                    thread_id serial NOT NULL REFERENCES threads,
                                    PRIMARY KEY (user_id, thread_id)
                                );";

}
