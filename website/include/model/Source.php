<?php
require_once 'include/model/DataFieldContainer.php';

class Source {
    
    private $db;
    private $fields;
    
    static function create($db) {
        $obj = new Source($db);
        $obj->fields = new DataFieldContainer(array(
            'id' => '',
            'abbreviation' => '',
            'year' => '',
            'text' => '',
            'updated' => ''));
        return $obj;
    }
    
    static function createFromValues($db, $values) {
        $obj = new Source($db);
        $obj->fields = new DataFieldContainer($values);
        return $obj;
    }
    
    /**
     * Import one object from a parsed XML import file.
     * 
     * @param (Db) $db database object to use
     * @param (SimpleXMLElement) $element an element representing the object.
     * Fields are child elements of this element.
     * @return (Source) a new Source (not saved to the database)
     */
    static function createFromXml($db, $element) {
        $fields = array('id' => (string)$element->source_id,
                        'abbreviation' => (string)$element->abbreviation,
                        'year' => (string)$element->year,
                        'text' => (string)$element->text,
                        'updated' => (string)$element->updated);
        return self::createFromValues($db, $fields);
    }
    
    static function createFromDb($db, $id) {
        $rows = $db->queryP('
            SELECT source_id AS id,
                   abbreviation,
                   year, 
                   text,
                   updated
            FROM sources
            WHERE source_id = ?',
            array($id));
        $obj = new Source($db);
        $obj->fields = new DataFieldContainer($rows[0]);
        return $obj;
    }
    
    static function createList($db, $pageIndex, $pageSize) {
        $list = array();
        $rows = $db->queryP('
            SELECT source_id AS id,
                   abbreviation,
                   year, 
                   text,
                   updated
            FROM sources
            ORDER BY abbreviation',
            array());
        foreach ($rows as $row) {
            $list[] = self::createFromValues($db, $row);
        }
        return $list;
    }
    
    function __construct($db) {
        $this->db = $db;
    }
    
    function saveAsNew() {
        $this->db->execP('
            INSERT INTO sources (abbreviation,
                                 year,
                                 text)
            VALUES (?, ?, ?)',
            array($this->abbreviation(),
                  $this->year(),
                  $this->text()));
    }
    
    function error($field) {
        if ($field === 'text') {
            return 'Wrong, all wrong.';
        } else {
            return '';
        }
    }
    
    function id() {
        return $this->fields->get('id');
    }
        
    function idHtml() {
        return $this->fields->getHtml('id');
    }
    
    function abbreviation() {
        return $this->fields->get('abbreviation');
    }
    
    function abbreviationHtml() {
        return $this->fields->getHtml('abbreviation');
    }
        
    function year() {
        return $this->fields->get('year');
    }
    
    function yearHtml() {
        return $this->fields->getHtml('year');
    }
    
    function text() {
        return $this->fields->get('text');
    }
    
    function textHtml() {
        return $this->fields->getHtml('text');
    }
    
    function updated() {
        return $this->fields->get('updated');
    }
    
    function updatedHtml() {
        return $this->fields->getHtml('updated');
    }
}