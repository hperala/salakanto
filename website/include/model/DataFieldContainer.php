<?php

class DataFieldContainer {
    
    private $fields;
    
    function __construct($fields) {
        $this->fields = $fields;
    }
    
    function get($key) {
        if (!array_key_exists($key, $this->fields)) {
            throw new Exception("Field not available: $key");
        }
        return $this->fields[$key];
    }
    
    function getHtml($key) {
        return $this->get($key);
    }
    
    function set($key, $value) {
        $this->fields[$key] = $value;
    }
}