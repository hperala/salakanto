<?php
require_once 'include/model/DataFieldContainer.php';

class Settings {
    
    private $db;
    private $fields;
    
    static function createFromDb($db) {
    	$rows = $db->queryP('
            SELECT BIN(allow_signup) AS allow_signup
            FROM settings',
    		array());
    	$obj = new Settings($db);
    	$obj->fields = new DataFieldContainer($rows[0]);
    	$obj->fields->set('allow_signup', 
    			          (int)$obj->fields->get('allow_signup'));
    	return $obj;
    }
    
    function __construct($db) {
        $this->db = $db;
    }
    
    function allowSignup() {
        return (bool)$this->fields->get('allow_signup');
    }
}