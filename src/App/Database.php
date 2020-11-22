<?php

namespace App;

use PDO;
use PDOException;

/**
 * PDO Database Class
 * Connect to database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */
class Database
{

    function __construct() {
        $this->init();
    }



    private $address = NO_SQL_ADDRESS;
    private $type = DB_TYPE;
    private $host = DB_HOST;
    private $port = DB_PORT;
    private $name = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    protected $dbHandler;
    protected $stmt;

    /**
     * Set DSN and create a PDO
     *
     * @return void
     */
    function init()
    {
        if ($this->address !== '') {
            $dsn = $this->type . ':' . $this->address;
        } else {
            $dsn = $this->type . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->name;
        }

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->dbHandler = new PDO($dsn, $this->user, $this->pass, $options);
            $this->dbHandler->exec("set names utf8mb4");
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

}


