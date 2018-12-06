<?php

class Database
{

    protected $config;
    protected $connection;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        extract($this->config);
        /**
         * @var string $host
         * @var string $port
         * @var string $dbname
         * @var string $user
         * @var string $password
         */
        $this->connection = pg_connect("$host $port $dbname $user $password");
        if (!$this->connection) {
            exit("Connect failed: " . pg_last_error());
        }
        return $this->connection;
    }

}
