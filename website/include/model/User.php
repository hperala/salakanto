<?php
require_once 'include/library/PasswordHash.php';

class User {
    
    /**
     * PHPass: "Base-2 logarithm of the iteration count used for password 
     * stretching".
     */   
    const HASH_COST_LOG2 = 8;
    
    /**
     * PHPass: "Do we require the hashes to be portable to older systems (less
     * secure)?"
     */
    const HASH_PORTABLE = false;
    
    private $db;
    private $fields;
    private static $rootUserExists = null;
    
    /**
     * Initializes session and creates a User [. . .]
     * 
     * @param unknown $db
     * @return User
     */
    static function createAndStartSession($db) {
        session_start();
        $user = new User($db);
        if (self::isLoggedIn()) {
            $user->load($_SESSION['user_id']);
        } 
        return $user;        
    }
    
    static function createFromValues($db, $values) {
        $obj = new User($db);
        $values['passwd'] = self::getHash($values['passwd']);
        $values['created'] = '-';
        $obj->fields = new DataFieldContainer($values);
        return $obj;
    }
    
    static function findIdByUsername($db, $username) {
        $rows = $db->queryP('
            SELECT user_id
            FROM users
            WHERE name = ?',
            array($username));
        if (count($rows) > 0) {
            return $rows[0]['user_id'];
        } else {
            return false;
        }
    }
    
    static function getHash($password) {
        $hasher = new PasswordHash(self::HASH_COST_LOG2, self::HASH_PORTABLE);
        $hash = $hasher->HashPassword($password);
        
        // PHPass: "the shortest valid password hash encoding string that 
        // phpass can currently return is 20 characters long".
        //
        if (strlen($hash) < 20) {
            throw new Exception('Failed to hash new password');
        }
        
        return $hash;
    }
    
    static function verifyPassword($password, $expectedHash) {
        $hasher = new PasswordHash(self::HASH_COST_LOG2, self::HASH_PORTABLE);
        return $hasher->CheckPassword($password, $expectedHash);
    }
    
    static function rootUserExists($db) {
        if (self::$rootUserExists !== null) {
            return self::$rootUserExists; 
        }
        
        $rows = $db->queryP('
            SELECT COUNT(user_id) AS num
            FROM users
            WHERE user_type_id = ?',
            array(USER_TYPE_ID_ROOT));
        self::$rootUserExists = $rows[0]['num'] > 0; 
        return self::$rootUserExists;
    }
    
    static function isValidUsername($username) {
        return array('success' => true, 'message' => '');
    }
    
    static function isValidPassword($password) {
        return array('success' => true, 'message' => '');
    }
    
    function __construct($db) {
        $this->db = $db;
    }
    
    static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    function login($username, $password) {
        $wrongNameOrPass = _('The username or password is incorrect.');
        
        $id = self::findIdByUsername($this->db, $username);
        if ($id === false) {
            return array('success' => false,
                         'message' => $wrongNameOrPass);
        }
        $this->load($id);
        if (!self::verifyPassword($password, $this->fields->get('passwd'))) {
            return array('success' => false,
                         'message' => $wrongNameOrPass);
        }
        $this->setStatusLoggedIn();
        
        return array('success' => true,
                     'message' => '');
    }
    
    function loginWithoutCheck() {
        $this->setStatusLoggedIn();
    }
    
    function logout() {
        $_SESSION = array();
        if (session_id() != '' || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 2592000, '/');
        }
        session_destroy();
    }
    
    function load($id) {
        $rows = $this->db->queryP('
            SELECT user_id AS id,
                   name,
                   passwd,
                   user_type_id,
                   created
            FROM users
            WHERE user_id = ?',
            array($id));
        $this->fields = new DataFieldContainer($rows[0]);
    }
    
    function saveAsNew() {
        $this->db->execP('
            INSERT INTO users (name,
                               passwd,
                               user_type_id,
                               created)
            VALUES (?, ?, ?, NOW())',
            array($this->name(),
                  $this->fields->get('passwd'),
                  $this->type()));
        $this->fields->set('id', $this->db->lastInsertId());
    }
    
    function id() {
        return $this->fields->get('id');
    }
    
    function idHtml() {
        return $this->fields->getHtml('id');
    }
    
    function name() {
        return $this->fields->get('name');
    }
    
    function nameHtml() {
        return $this->fields->getHtml('name');
    }
    
    function type() {
        return $this->fields->get('user_type_id');
    }
    
    function typeHtml() {
        return $this->fields->getHtml('user_type_id');
    }
    
    function created() {
        return $this->fields->get('created');
    }
    
    function createdHtml() {
        return $this->fields->getHtml('created');
    }
    
    private function setStatusLoggedIn() {
        $_SESSION['user_id'] = $this->fields->get('id');
    }
}