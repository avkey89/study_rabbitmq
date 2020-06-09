<?php

namespace App\Classes;


class Database
{
    private static $instance = null;

    private $connect;

    private $selectDB;

    private $resultSelect;

    private function __construct () {}

    private function __clone () {}

    private function __wakeup () {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instance[$subclass])) {
            self::$instance[$subclass] = new static;
        }
        return self::$instance[$subclass];
    }

    public function connect($host, $login, $password)
    {
        $this->connect = mysqli_connect($host, $login, $password);
    }

    public function selectDB($name)
    {
        $this->selectDB = mysqli_select_db($this->connect, $name);
    }

    public function select($query)
    {
        $this->resultSelect = mysqli_query($this->connect, $query);

        return $this;
    }

    public function fetch()
    {
        $rows = [];
        while($row = mysqli_fetch_array($this->resultSelect)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function insert($table = '', array $fields = []): int
    {
        if (empty($table)) {
            throw new \Exception("'Table' not exists");
        }
        if (empty($fields)) {
            throw new \Exception("'Fields' not exists");
        }

        $sql = "INSERT INTO `".$table."` 
            (".implode(', ', array_keys($fields)).") 
            VALUES 
            (".implode(', ',array_values($fields)).")";

        $insert = mysqli_query($this->connect, $sql);
        if(!$insert) {
            throw new \Exception("No entry has been added");
        }
        if ((int)$this->connect->insert_id > 0) {
            return (int)$this->connect->insert_id;
        }

        return 0;
    }
}