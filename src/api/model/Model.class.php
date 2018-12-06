<?php

abstract class Model
{

    protected $create_string;
    protected $table_name;
    protected $db_name;
    protected $connection;

    public function __construct($connection)
    {
        include __DIR__ . "/../config.php";
        /**
         * @var array $config
         */
        $this->db_name = substr($config["dbname"], strpos($config["dbname"], "=") + 1);
        $this->table_name = lcfirst(get_class($this));
        $this->connection = $connection;
    }

    public function __set($property_name, $value)
    {
        $this->$property_name = $value;
    }

    public function __get($property_name)
    {
        return $this->$property_name;
    }

    public function createTable()
    {
        if ($this->connection) {
            pg_query($this->connection, $this->create_string);
        } else {
            exit("Connect failed: " . pg_last_error());
        }
        if (pg_last_error()) {
            exit("Error creating table: " . pg_last_error());
        }
    }

    public function selectAll($param)
    {
        $result = pg_fetch_all(pg_query($this->connection, "SELECT * FROM $this->table_name"));
        return $result;
    }

}
