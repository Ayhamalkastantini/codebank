<?php


namespace App;
use PDO;
use PDOException;

class BaseRepository extends Database
{

    /**
     * Prepare statement with query
     *
     * @param string $sql
     * @return void
     */
    public function query($sql)
    {
        $this->init();
        try {
            $this->stmt = $this->dbHandler->prepare($sql);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }


    /**
     * Bind values
     *
     * @param string $param
     * @param mixed $value
     * @param PDO data type $type
     * @return void
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }

        try {
            $this->stmt->bindValue($param, $value, $type);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }




    /**
     * Execute the prepared statement
     *
     * @return bool
     */
    public function execute()
    {
        try {
            return $this->stmt->execute();
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
            return false;
        }
    }

    /**
     * Get result set as array
     *
     * @return array
     */
    public function fetchAll()
    {
        try {
            $this->stmt->execute();
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }


    /**
     * Get single record as array
     *
     * @return array
     */
    public function fetch()
    {
        try {
            $this->stmt->execute();
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    /**
     * Get single record as object
     *
     * @return array
     */
    public function fetchAll_class($class)
    {
        try {
            $this->stmt->execute();
            return $this->stmt->fetchAll(PDO::FETCH_CLASS, $class);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }
}