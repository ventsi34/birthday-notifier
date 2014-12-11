<?php
namespace Core;
/**
 * Singletone connection and execute db queries
 *
 * @author Ventsislav Dimitrov
 */
class DB {
    static private $instance = null;
    private $db = null; 

    private function __construct() {
        try {
            $DBH = new \PDO("mysql:host=".__DB_HOST__.";dbname=".__DB_NAME__.";charset=utf8", __DB_USERNAME__, __DB_PASSWORD__);
            $DBH->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            $this->db = $DBH;
        } catch(\PDOException $e) {
            header('Content-Type: application/json');
            http_response_code($code);
            echo json_encode("We have a problem with our Database! Sorry about this.");
            exit();
        }
    }

    public static function init() {
        if(self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getDB() {
        return $this->db;
    }
}
