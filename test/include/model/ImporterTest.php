<?php
require_once 'include/model/Importer.php';

class ImporterTest extends PHPUnit_Framework_TestCase
{
    private $imp = null;
    private $db = null;
    
    function setUp() {
        $this->db = new DbStub();
        $this->imp = new Importer($this->db);
    }
    
    function testParse() {
        $doc = $this->getXmlDoc();

        $this->assertEquals('Gilthoniel', $doc->names->name[0]->title);
        $this->assertEquals('1945', $doc->sources->source[0]->year);
    }
    
    function testCreateAndSaveSources() {
        $doc = $this->getXmlDoc();
        $sources = $this->imp->createSources($doc);
        
        $this->assertEquals('1945', $sources[0]->year());
        
        $newIds = array();
        $this->imp->saveSources($sources, $newIds);
        
        $this->assertEquals(100, $newIds[1]);
    }
    
    function testCreateAndSaveNames() {
        $doc = $this->getXmlDoc();
        $nameIns = $this->imp->createNamesGetInstances($doc);
    
        $this->assertEquals('Gilthoniel', $nameIns['names'][0]->title());
    
        $newIds = array();
        $this->imp->saveNames($nameIns['names'], $newIds);
    
        $this->assertEquals(100, $newIds[0]);
    }
    
    function testCreateInstancesEmpty() {
        $doc = $this->getXmlDoc();
        $nameIns = $this->imp->createNamesGetInstances($doc);
            
        $newSourceIds = array();
        $newNameIds = array();
        $objs = $this->imp->createInstances($nameIns['instances'], 
                                            $newSourceIds, 
                                            $newNameIds);
        
        $this->assertEquals(0, count($objs));
    }
    
    function testCreateAndSaveInstances() {
        $doc = $this->getLongXmlDoc();
        
        $newSourceIds = array();
        $newNameIds = array();
        
        $sources = $this->imp->createSources($doc);
        $this->imp->saveSources($sources, $newSourceIds);
        
        $this->assertEquals(100, $newSourceIds[200]);
        $this->assertEquals(101, $newSourceIds[123]);
        
        $nameIns = $this->imp->createNamesGetInstances($doc);
        $this->imp->saveNames($nameIns['names'], $newNameIds);
        
        $this->assertEquals(102, $newNameIds[0]);
        
        $objs = $this->imp->createInstances($nameIns['instances'],
                                            $newSourceIds,
                                            $newNameIds);
        
        $this->assertEquals(2, count($objs));
        $this->assertEquals('Manwe', $objs[0]->text());
        $this->assertEquals('101', $objs[0]->sourceId());
        $this->assertEquals('102', $objs[0]->nameId());
        $this->assertEquals('Manwee', $objs[1]->text());
        $this->assertEquals('100', $objs[1]->sourceId());
        $this->assertEquals('102', $objs[1]->nameId());
        
        $this->imp->saveInstances($objs);
    }
    
    function getXmlDoc() {
        $xmlAsString = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<salakanto>
  <names>
    <name>
      <title>Gilthoniel</title>
      <text/>
      <index_coverage/>
      <other_coverage/>
      <secondary/>
      <notes/>
      <created>2009-10-25 15:58:38</created>
      <updated>2009-10-31 16:32:23</updated>
      <instances>
      </instances>
    </name>
  </names>
  <sources>
    <source>
      <source_id>1</source_id>
      <abbreviation>LR drafts</abbreviation>
      <year>1945</year>
      <text/>
      <updated>2009-10-25 15:56:39</updated>
    </source>
  </sources>
</salakanto>
XML;
        return $this->imp->parse($xmlAsString);
    }
    
    function getLongXmlDoc() {
        $xmlAsString = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<salakanto>
  <names>
    <name>
      <title>Manwë</title>
      <text/>
      <index_coverage/>
      <other_coverage/>
      <secondary/>
      <notes/>
      <created>2009-10-25 15:58:38</created>
      <updated>2009-10-31 16:32:23</updated>
      <instances>
        <instance>
          <text>Manwe</text>
          <note/>
          <ref>PE17:162</ref>
          <translation/>
          <etymology>a Quenya name, from [r]√MAN[/r] ‘good’ referring to unmarred persons and things</etymology>
          <source_id>123</source_id>
          <source_elaboration>s.v. MAN</source_elaboration>
          <updated>2009-10-31 15:31:52</updated>
        </instance>
        <instance>
          <text>Manwee</text>
          <note/>
          <ref>PE17:189–90</ref>
          <translation/>
          <etymology>a Quenya name; [w]-we[/w] is “in origin a separate word WĒ (WE’E ?)” and is probably related to Q [w]vē̆[/w] ‘as, like’ [this is the final form of the note; rejected versions seem to connect [w]-we[/w] with a meaning  ‘life’ or ‘person’]</etymology>
          <source_id>200</source_id>
          <source_elaboration>s.v. -we</source_elaboration>
          <updated>2009-10-31 15:41:34</updated>
        </instance>
      </instances>
    </name>
  </names>
  <sources>
    <source>
      <source_id>200</source_id>
      <abbreviation>AV 1</abbreviation>
      <year>1931</year>
      <text>The first version of the Annals of Valinor, in The Shaping of Middle-earth.</text>
      <updated>2012-02-04 15:51:13</updated>
    </source>
    <source>
      <source_id>123</source_id>
      <abbreviation>LR drafts</abbreviation>
      <year>1945</year>
      <text/>
      <updated>2009-10-25 15:56:39</updated>
    </source>
  </sources>
</salakanto>
XML;
        return $this->imp->parse($xmlAsString);
    }
}

class DbStub {
    private $lastInserted = 100;
    public $exception = null;
    
    function execP($query, $params) {
    }
    
    function queryP($query, $params) {
        if ($this->exception !== null) throw $this->exception;
        
        return array();
    }
    
    function lastInsertId() {
        return $this->lastInserted++;
    }
}