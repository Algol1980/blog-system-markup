<?php
namespace app\components;

class MySQLConnector {

    /**
     * @var MySQLConnector
     */
    private static $instance;

    private $dbName = 'blogdb';
    private $username = 'root';
    private $password = '';
    private $host = 'localhost';

    private $dbh;

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPDO() {
        return $this->dbh;
    }

    private function __construct() {
        try {
            $this->dbh = new \PDO("mysql:host=".$this->host.";dbname=".$this->dbName, $this->username, $this->password);
        } catch (Exception $error) {
            throw new Exception("Can not connect to db");
        }
    }
}