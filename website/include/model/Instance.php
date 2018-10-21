<?php
require_once 'include/model/DataFieldContainer.php';

class Instance {
    
    private $db;
    private $fields;
    
    static function create($db) {
        $obj = new Instance($db);
        $obj->fields = new DataFieldContainer(array(
            'id' => '',
            'name_id' => '',
            'source_id' => '',
            'source_elaboration' => '',
            'text' => '',
            'note' => '',
            'ref' => '',
            'translation' => '',
            'etymology' => '',
            'updated' => ''));
        return $obj;
    }
    
    static function createFromValues($db, $values) {
        $obj = new Instance($db);
        $obj->fields = new DataFieldContainer($values);
        return $obj;
    }
    
    /**
     * Import one object from a parsed XML import file.
     *
     * @param (Db) $db database object to use
     * @param (SimpleXMLElement) $element an element representing the object.
     * Fields are child elements of this element.
     * @param (integer as string) $nameId ID of the name
     * @param (integer as string) $sourceId ID of the source  
     * @return (Instance) a new Instance (not saved to the database)
     */
    static function createFromXml($db, $element, $nameId, $sourceId) {
        $fields = array('source_id' => $sourceId,
                        'name_id' => $nameId,
                        'text' => (string)$element->text,
                        'note' => (string)$element->note,
                        'ref' => (string)$element->ref,
                        'translation' => (string)$element->translation,
                        'etymology' => (string)$element->etymology,
                        'source_elaboration' 
                            => (string)$element->source_elaboration,
                        'updated' => (string)$element->updated);
        return self::createFromValues($db, $fields);
    }
    
    static function createFromDb($db, $id) {
        $rows = $db->queryP('
            SELECT instance_id AS id,
                   name_id,
                   source_id,
                   source_elaboration,
                   text,
                   note,
                   ref,
                   translation,
                   etymology,
                   updated
            FROM instances
            WHERE instance_id = ?',
            array($id));
        $obj = new Instance($db);
        $obj->fields = new DataFieldContainer($rows[0]);
        return $obj;
    }
    
    static function createList($db, $pageIndex, $pageSize) {
        $list = array();
        $rows = $db->queryP('
            SELECT instance_id AS id,
                   name_id,
                   source_id,
                   source_elaboration,
                   text,
                   note,
                   ref,
                   translation,
                   etymology,
                   updated
            FROM instances
            ORDER BY text',
            array());
        foreach ($rows as $row) {
            $list[] = self::createFromValues($db, $row);
        }
        return $list;
    }
    
    static function createListByNameId($db, $nameId, $pageIndex, $pageSize) {
        $list = array();
        $rows = $db->queryP('
            SELECT instance_id AS id,
                   name_id,
                   instances.source_id,
                   source_elaboration,
                   instances.text,
                   note,
                   ref,
                   translation,
                   etymology,
                   instances.updated,
                   sources.abbreviation AS source_abbreviation
            FROM instances
            INNER JOIN sources ON instances.source_id = sources.source_id
            WHERE name_id = ?
            ORDER BY instances.text',
            array($nameId));
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
            INSERT INTO instances (name_id,
                                   source_id,
                                   source_elaboration,
                                   text,
                                   note,
                                   ref,
                                   translation,
                                   etymology) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            array($this->nameId(),
                  $this->sourceId(),
                  $this->sourceElaboration(),
                  $this->text(),
                  $this->note(),
                  $this->ref(),
                  $this->translation(),
                  $this->etymology()));
    }
    
    function error($field) {
        return '';
    }
    
    function id() {
        return $this->fields->get('id');
    }
    
    function idHtml() {
        return $this->fields->getHtml('id');
    }
    
    function nameId() {
        return $this->fields->get('name_id');
    }
    
    function nameIdHtml() {
        return $this->fields->getHtml('name_id');
    }
    
    function sourceId() {
        return $this->fields->get('source_id');
    }
    
    function sourceIdHtml() {
        return $this->fields->getHtml('source_id');
    }
    
    function sourceElaboration() {
        return $this->fields->get('source_elaboration');
    }
    
    function sourceElaborationHtml() {
        return $this->fields->getHtml('source_elaboration');
    }

    function text() {
        return $this->fields->get('text');
    }
    
    function textHtml() {
        return $this->fields->getHtml('text');
    }
    
    function note() {
        return $this->fields->get('note');
    }
    
    function noteHtml() {
        return $this->fields->getHtml('note');
    }
    
    function ref() {
        return $this->fields->get('ref');
    }
    
    function refHtml() {
        return $this->fields->getHtml('ref');
    }
    
    function translation() {
        return $this->fields->get('translation');
    }
    
    function translationHtml() {
        return $this->fields->getHtml('translation');
    }
    
    function etymology() {
        return $this->fields->get('etymology');
    }
    
    function etymologyHtml() {
        return $this->fields->getHtml('etymology');
    }
    
    function updated() {
        return $this->fields->get('updated');
    }
    
    function updatedHtml() {
        return $this->fields->getHtml('updated');
    }
    
    function sourceAbbreviation() {
        return $this->fields->get('source_abbreviation');
    }
    
    function sourceAbbreviationHtml() {
        return $this->fields->getHtml('source_abbreviation');
    }
}