<?php
require_once 'include/model/Markup.php';

class MarkupTest extends PHPUnit_Framework_TestCase
{
    function testEmpty() {
        $expected = '';
        $m = new Markup('');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testPlainText() {
        $expected = 'aaa';
        $m = new Markup('aaa');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testHtml() {
        $expected = 'aaa <i class="el">bbb</i>';
        $m = new Markup('aaa [w]bbb[/w]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testTrailingText() {
        $expected = '<i class="el">bbb</i> aaa';
        $m = new Markup('[w]bbb[/w] aaa');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testNoLeadingOrTrailingWhitespace() {
        $expected = '<p>a</p>';
        $m = new Markup('[p]a[/p]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testErrorMissingClosingTag() {
        $m = new Markup('aaa [w]bbb');
        $this->assertEquals(Markup::MISSING_CLOSING_TAG, $m->error());
    }
    
    function testErrorMissingClosingTagNested() {
        $m = new Markup('aaa [p][w]bbb');
        $this->assertEquals(Markup::MISSING_CLOSING_TAG, $m->error());
    }
    
    function testErrorMissingOpeningTag() {
        $m = new Markup('aaa bbb[/w]');
        $this->assertEquals(Markup::MISSING_OPENING_TAG, $m->error());
    }
    
    function testSquareBrackets() {
        $expected = ']][][][[';
        $m = new Markup(']][][][[');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testHtmlReservedCharacters() {
        $expected = '<p>a &lt;p&gt; b</p>';
        $m = new Markup('[p]a <p> b[/p]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testComplexDocument() {
        $input = '
[p]
Aaaa [w]bbb[b]bb[/w] ccc.
[/p]

[p]D [w]e[/w][w]f[/w][/p] [i]g[/i] xyz [r]xyz[/r]';
        $expected = '
<p>
Aaaa <i class="el">bbb[b]bb</i> ccc.
</p>

<p>D <i class="el">e</i><i class="el">f</i></p> <i>g</i> xyz <span class="root">xyz</span>';
        $m = new Markup($input);
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testLink() {
        $expected = '<a href="dot.com/?a=1&amp;b=2">link</a>';
        $m = new Markup('[l=dot.com/?a=1&b=2]link[/l]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testLinkEmptyUrl() {
        $expected = '<a href="">link</a>';
        $m = new Markup('[l=]link[/l]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testBrokenLinkTag() {
        $expected = '[l=pseudolink';
        $m = new Markup('[l=pseudolink');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testContainsBlockElements() {
        $m = new Markup('aaaa [w]bbb[/w]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertFalse($m->containsBlockElements());
        
        $m2 = new Markup('aaaa [p][w]bbb[/w] ccc[/p]');
        $this->assertEquals(Markup::OK, $m2->error());
        $this->assertTrue($m2->containsBlockElements());
    }
    
    function testFinalCharacter() {
        $expected = 'aaa <i class="el">bbb</i>c';
        $m = new Markup('aaa [w]bbb[/w]c');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testNomarkup() {
        $expected = 'aaa [w]ww c';
        $m = new Markup('aaa [nomarkup][w][/nomarkup]ww c');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testNomarkupClosingOptional() {
        $expected = 'aaa [w]ww c';
        $m = new Markup('aaa [nomarkup][w]ww c');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testNomarkupNestingNotAllowed() {
        $m = new Markup('aa[nomarkup]a [nomarkup][w][/nomarkup]ww[/nomarkup] c');
        $this->assertEquals(Markup::MISSING_OPENING_TAG, $m->error());
    }
    
    function testNomarkupEmpty() {
        $expected = 'aaa www c';
        $m = new Markup('aaa [nomarkup][/nomarkup]www c');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testNomarkupEscapeOneChar() {
        $expected = 'aaa [w]ww c';
        $m = new Markup('aaa [nomarkup][[/nomarkup]w]ww c');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
    
    function testNomarkupMulti() {
        $expected = 'w[w]w[/w] <i class="el">bbb</i> www';
        $m = new Markup('[nomarkup]w[w]w[/w][/nomarkup] [w]bbb[/w] [nomarkup]www[/nomarkup]');
        $this->assertEquals(Markup::OK, $m->error());
        $this->assertEquals($expected, $m->html());
    }
}
