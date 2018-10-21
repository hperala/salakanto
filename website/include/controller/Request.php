<?php
require_once 'config.php';

class Request {
    
    const OPEN = 1;
    const OPEN_EDIT = 2;
    const OPEN_CREATE = 3;
    const OPEN_DELETE = 4;
    const UPDATE = 5;
    const CREATE = 6;
    const DELETE = 7;
    const UPLOAD = 8;
    
    private $post;
    private $get;
    
    static function createFromEnv() {
        return new Request($_POST, $_GET);
    }
    
    static function baseUrl() {
        $host = $_SERVER['HTTP_HOST'];
        $dir = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return "http://$host$dir/";
    }
    
    function __construct($post, $get) {
        $this->post = $post;
        $this->get = $get;
    }
    
    function param($key) {
        if (isset($this->post[$key])) {
            return $this->post[$key];
        } else if (isset($this->get[$key])) {
            return $this->get[$key];
        } else {
            throw Exception("Request does not include a value for key: $key");
        }
    }
    
    function hasParam($key) {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }
    
    function id() {
        if (isset($this->get['id'])) {
            return $this->get['id'];
        }
        return '';
    }
    
    function type() {
        if ($_FILES) {
            return self::UPLOAD;
        } elseif (count($this->post) > 0 && isset($this->get['id'])) {
            return self::UPDATE;
        } elseif (count($this->post) > 0) {
            return self::CREATE;
        } elseif ($this->action() === 'delete') {
            return self::DELETE;
        } elseif ($this->action() === 'confirmdelete') {
            return self::OPEN_DELETE;
        } elseif ($this->action() === 'edit' && isset($this->get['id'])) {
            return self::OPEN_EDIT;
        } elseif ($this->action() === 'edit') {
            return self::OPEN_CREATE;
        } else {
            return self::OPEN;
        }
    }
    
    function action() {
        if (isset($this->get['a'])) {
            return $this->get['a'];
        }
        return '';
    }
    
    function initLocalization() {
        $language = $this->preferredLocale();
        
        // Using "putenv('LANG=' . $language);" may not be allowed on the server

        setlocale(LC_ALL, $language);    
        $domain = 'messages';
        bindtextdomain($domain, 'locale');
        bind_textdomain_codeset($domain, 'UTF-8');    
        textdomain($domain);
    }
    
    function preferredLocale() {
        global $LANG_TO_LOCALE;
        
        $match = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if ($match !== null
            && array_key_exists($match, $LANG_TO_LOCALE)) {
            $locale = $LANG_TO_LOCALE[$match];
        } else {
            $locale = DEFAULT_LOCALE;
        }
        return $locale;
    }
}