<?php
require_once 'include/view/Pagination.php';

class PaginationTest extends PHPUnit_Framework_TestCase
{
    /** Prev & next links */
    const NUM_OTHER_LINKS = 2;
    
    function testEmpty() {
        $p = new Pagination(0, 10, 0);
		
        $this->assertEquals(0, $p->firstDbIndex());
        $this->assertEquals(0, $p->lastDbIndex());
        $this->assertEquals(0, $p->numItems());
    }
	
    function testPageSizeEqualsNumDbItems() {
        $p = new Pagination(10, 10, 0);
	
        $this->assertEquals(0, $p->firstDbIndex());
        $this->assertEquals(9, $p->lastDbIndex());
        $this->assertEquals(NUM_OTHER_LINKS + 1, $p->numItems());
	}
}