<?php
namespace Framework;

class DB {
    static protected $instance = null;

    protected $connection = null;
    public static $integerTypes = [
        'INT',
        'BIGINT',
        'SMALLINT',
        'TINYINT',
        'MEDIUMINT',
        'DECIMAL'
    ];
    protected function __construct() {
        $this->connection = new \PDO("mysql:host=" .$_ENV['DB_IP']. ";dbname=" .$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    }

    public function getConnection(){
        return $this->connection;
    }

    static public function getInstance(){
        if (!(static::$instance instanceof static)) {
            static::$instance = new static();
        }

        return static::$instance->getConnection();
    }

    static public function getType($type) {
        return in_array($type, DB::$integerTypes) ? 'i' : 's';
    }
}