<?php
require_once 'include/model/DataFieldContainer.php';

class Name {
    
    private $db;
    private $fields;
    
    static function create($db) {
        $obj = new Name($db);
        $obj->fields = new DataFieldContainer(array('title' => '',
                                                    'text' => ''));
        return $obj;
    }
    
    static function createFromValues($db, $values) {
        $obj = new Name($db);
        $obj->fields = new DataFieldContainer($values);
        return $obj;
    }
    
    /**
     * Import one object from a parsed XML import file. Does not import
     * instances of this name.
     *
     * @param (Db) $db database object to use
     * @param (SimpleXMLElement) $element an element representing the object.
     * Fields are child elements of this element.
     * @return (Name) a new Name (not saved to the database)
     */
    static function createFromXml($db, $element) {
        $fields = array('title' => (string)$element->title,
                        'text' => (string)$element->text,
                        'index_coverage' => (string)$element->index_coverage,
                        'other_coverage' => (string)$element->other_coverage,
                        'secondary' => (string)$element->secondary,
                        'notes' => (string)$element->notes,
                        'created' => (string)$element->created,
                        'updated' => (string)$element->updated);
        return self::createFromValues($db, $fields);
    }
    
    static function createFromDb($db, $id) {
        $rows = $db->queryP('
            SELECT names.name_id AS id,
                   title,
                   text, 
                   index_coverage,
                   other_coverage,
                   secondary,
                   notes,
                   created,
                   updated,
                   COUNT(etym_null) AS num_etymologies, 
                   COUNT(trans_null) AS num_translations 
            FROM names 
                INNER JOIN 
                (
                    SELECT name_id, 
                           (CASE CHAR_LENGTH(etymology) WHEN 0 THEN NULL ELSE \'-\' END) AS etym_null, 
                           (CASE CHAR_LENGTH(translation) WHEN 0 THEN NULL ELSE \'-\' END) AS trans_null 
                    FROM instances
                ) instances 
                ON names.name_id = instances.name_id
            WHERE names.name_id = ?
            GROUP BY names.name_id',
            array($id));
        $obj = new Name($db);
        $obj->fields = new DataFieldContainer($rows[0]);
        return $obj;
    }
    
    static function createList($db, $pageIndex, $pageSize) {
        $list = array();
        $rows = $db->queryP('
            SELECT names.name_id AS id,
                   title,
                   text, 
                   index_coverage,
                   other_coverage,
                   secondary,
                   notes,
                   created,
                   updated,
                   COUNT(etym_null) AS num_etymologies, 
                   COUNT(trans_null) AS num_translations 
            FROM names 
                INNER JOIN 
                (
                    SELECT name_id, 
                           (CASE CHAR_LENGTH(etymology) WHEN 0 THEN NULL ELSE \'-\' END) AS etym_null, 
                           (CASE CHAR_LENGTH(translation) WHEN 0 THEN NULL ELSE \'-\' END) AS trans_null 
                    FROM instances
                ) instances 
                ON names.name_id = instances.name_id
            GROUP BY names.name_id',
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
            INSERT INTO names (title, 
                               text, 
                               index_coverage,
                               other_coverage,
                               secondary,
                               notes) 
            VALUES (?, ?, ?, ?, ?, ?)',
            array($this->title(),
                  $this->text(),
                  $this->indexCoverage(),
                  $this->otherCoverage(),
                  $this->secondary(),
                  $this->notes()));
    }
    
    function error($field) {
        if ($field === 'title') {
            return 'Wrong, all wrong.';
        } else {
            return '';
        }
    }
    
    function id() {
        return $this->fields->get('id');
    }
    
    function idHtml() {
        return $this->fields->getHtml('title');
    }
    
    function title() {
        return $this->fields->get('title');
    }
    
    function titleHtml() {
        return $this->fields->getHtml('title');
    }
    
    function text() {
        return $this->fields->get('text');
    }
    
    function textHtml() {
        return $this->fields->getHtml('text');
    }
    
    function numTranslations() {
        return $this->fields->get('num_translations');
    }
    
    function numTranslationsHtml() {
        return $this->fields->getHtml('num_translations');
    }
    
    function numEtymologies() {
        return $this->fields->get('num_etymologies');
    }
    
    function numEtymologiesHtml() {
        return $this->fields->getHtml('num_etymologies');
    }
    
    function indexCoverage() {
        return $this->fields->get('index_coverage');
    }
    
    function indexCoverageHtml() {
        return $this->fields->getHtml('index_coverage');
    }
    
    function otherCoverage() {
        return $this->fields->get('other_coverage');
    }
    
    function otherCoverageHtml() {
        return $this->fields->getHtml('other_coverage');
    }
    
    function secondary() {
        return $this->fields->get('secondary');
    }
    
    function secondaryHtml() {
        return $this->fields->getHtml('secondary');
    }
    
    function notes() {
        return $this->fields->get('notes');
    }
    
    function notesHtml() {
        return $this->fields->getHtml('notes');
    }
    
    function created() {
        return $this->fields->get('created');
    }
    
    function createdHtml() {
        return $this->fields->getHtml('created');
    }
    
    function updated() {
        return $this->fields->get('updated');
    }
    
    function updatedHtml() {
        return $this->fields->getHtml('updated');
    }
}