<?php
require_once 'config.php';

class Db {

    private $pdo;
    private $server;
    private $user;
    private $pass;
    private $db;
    private $dbSource;
    
    static function createFromConfig() {
        return new Db(SERVER, USER, PASS, DB);
    }
    
    function __construct($server, $user, $pass, $db) {        
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->dbSource = "mysql:host=$server;dbname=$db;charset=utf8";
    }
    
    function connect() {
        $this->pdo = new PDO($this->dbSource, $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    function close() {
        $this->pdo = null;
    }
    
    function escapeString($unescaped) {
        return '';
        //return $this->mysqli->real_escape_string($unescaped);
    }
    
    function escapeStrings($unescaped) {
        return '';
        //return array_map(array($this, 'escapeString'), $unescaped);
    }
    
    function query($query) {
        
    }
    
    function exec($query) {
        return $this->pdo->exec($query);
    }
    
    function execP($query, $params) {
        $stmt = $this->pdo->prepare($query);
        $rows = array();
        $stmt->execute($params);
    }
    
    function queryP($query, $params) {
        $stmt = $this->pdo->prepare($query);
        $rows = array();
        if ($stmt->execute($params)) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
    
    function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    function commit() {
        return $this->pdo->commit();
    }
    
    function rollBack() {
        return $this->pdo->rollBack();
    }
}