<?php
class Pagination {
    
    const PREV = 1;
    const NEXT = 2;
    const PAGE = 3;
    
    private $items;
    private $pageSize;
    private $page;
    private $firstDbIndex;
    private $lastDbIndex;
    private $navItems;
    
    /**
     * 
     * @param unknown $items
     * @param unknown $pageSize
     * @param unknown $page 0-based index
     */
    function __construct($items, $pageSize, $page) {
        $this->items = $items;
        $this->pageSize = $pageSize;
        $this->page = $page;
        
        $this->calculate();
    }
    
    function firstDbIndex() {
        return $this->firstDbIndex;
    }
    
    function lastDbIndex() {
        return $this->lastDbIndex;
    }
    
    function numItems() {
        return count($this->navItems);
    }
    
    function type($index) {
        if ($index == 0) return self::PREV;
        if ($index == 4) return self::NEXT;
        return self::PAGE;
    }
    
    function number($index) {
        return $index;
    }
    
    function link($index) {
        if ($index == 1) return '..';
        return null;
    }
    
    private function calculate() {
        $this->firstDbIndex = 0;
        $this->lastDbIndex = 0;
        $this->navItems = array();
    }
}