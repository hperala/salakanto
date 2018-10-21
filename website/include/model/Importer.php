<?php
require_once 'include/model/Source.php';
require_once 'include/model/Name.php';
require_once 'include/model/Instance.php';

class Importer {
    
    private $db;
    
    function __construct($db) {
        $this->db = $db;
    }
    
    function import($path) {
        $succeededTemplate = _('Imported %d names (%d instances) and %d sources.');
        $failedTemplate = _('Importing failed: %s');
        
        $this->db->beginTransaction();
        try {
            $fileContents = $this->read($path);
            $xmlDoc = $this->parse($fileContents);
            
            // key = old ID, value = new ID
            $newSourceIds = array();
            // key = index in array $nameIns, value = new ID
            $newNameIds = array();
            
            $sources = $this->createSources($xmlDoc);
            $this->saveSources($sources, $newSourceIds);
            
            $nameIns = $this->createNamesGetInstances($xmlDoc);
            $this->saveNames($nameIns['names'], $newNameIds);
            
            $ins = $this->createInstances($nameIns['instances'], 
                                          $newSourceIds, 
                                          $newNameIds);
            $this->saveInstances($ins);
            
            $this->db->commit();
            return array('success' => true,
                         'message' => sprintf($succeededTemplate, 
                                              count($nameIns['names']),
                                              count($ins),
                                              count($sources)));
        } catch (Exception $e) {
            $this->db->rollBack();
            return array('success' => false,
                         'message' => sprintf($failedTemplate, 
                                              $e->getMessage()));
        }
    }
    
    function read($path) {
        $fileContents = file_get_contents($path);
        if ($fileContents === false) {
            throw new Exception(error_get_last()['message']);
        }
        return $fileContents;
    }
    
    function parse($fileContents) {
        return new SimpleXMLElement($fileContents);
    }
    
    function createSources($xmlDoc) {
        $sources = array();
        foreach ($xmlDoc->sources->source as $source) {
            $sources[] = Source::createFromXml($this->db, $source);
        }
        return $sources;
    }
    
    function createNamesGetInstances($xmlDoc) {
        $result = array('names' => array(),
                        'instances' => array());
        foreach ($xmlDoc->names->name as $name) {
            $result['names'][] = Name::createFromXml($this->db, $name);            
            $result['instances'][] = $name->instances;
        }
        return $result;
    }
    
    function createInstances($xmlElems, $newSourceIds, $newNameIds) {
        $instances = array();
        foreach ($xmlElems as $nameIndex => $xmlElem) {
            foreach ($xmlElem->instance as $ins) {
                $nameId = $newNameIds[$nameIndex];
                $oldSourceId = (int)$ins->source_id;
                $sourceId = $newSourceIds[$oldSourceId];
                $instances[] = Instance::createFromXml($this->db, 
                                                       $ins, 
                                                       $nameId,
                                                       $sourceId);
            }
        }
        return $instances;
    }
    
    function saveSources($sources, &$newSourceIds) {
        $this->saveItems($sources, $newSourceIds);        
    }
    
    function saveNames($names, &$newNameIds) {
        foreach ($names as $oldIndex => $name) {
            $name->saveAsNew();
            $newNameIds[$oldIndex] = $this->db->lastInsertId();
        }
    }
    
    function saveInstances($instances) {
        foreach ($instances as $ins) {
            $ins->saveAsNew();
        }
    }
    
    private function saveItems($items, &$newIds) {
        foreach ($items as $item) {
            $oldId = $item->id();
            $item->saveAsNew();
            $newIds[$oldId] = $this->db->lastInsertId();
        }
    }
}