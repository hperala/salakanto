<?php

/**
 * A state used internally by the markup parser.
 *
 */
class State {
    protected $context;    
    
    function __construct($context) {
        $this->context = $context;
    }
    
    /**
     * Processes a token that contains both a tag and the text before it.
     *  
     * @param (array) $token An array with keys "done" (boolean), "tag_name" 
     * (string or null), "preceding_text" (string) and "attributes" (array or 
     * null). If "done" is true, this is the last token in the input. In the 
     * last token, "tag_name" can be either null or a tag name. It is never 
     * null otherwise. If "tag_name" is null, this token contains the text 
     * between the last tag and the end of input. "preceding_text" is the text
     * between the previous tag and the tag in this token (if any). If 
     * "attributes" is not null, if contains attribute names mapped to 
     * attribute values. These should be passed unchanged to the appropriate 
     * output method.
     *   
     */
    function processToken($token) {
        
    }
    
    protected function outputTag($token) {
        if ($token['attributes'] !== null) {
            $this->context->outputTagWithAttr($token['tag_name'], 
                                              $token['attributes']);
        } else {
            $this->context->outputHtmlTag($token['tag_name']);
        }
    }
}

/**
 * Root-level state outside any element.
 *
 */
class RootLevelState extends State {
    
    function __construct($context) {
        parent::__construct($context);
    }

    function processToken($token) {
        $this->context->outputText($token['preceding_text']);
        if ($token['tag_name'] !== null) {
            if ($this->context->isOpeningTag($token['tag_name'])) {
                $this->outputTag($token);
                if ($this->context->isBlockTag($token['tag_name'])) {
                    $this->context->pushState(
                            new BlockState($this->context, 
                                           $token['tag_name']));
                } elseif ($this->context->isInlineTag($token['tag_name'])) {
                    $this->context->pushState(
                            new InlineState($this->context, 
                                            $token['tag_name']));
                }
            } else {
                $this->context->setError(Markup::MISSING_OPENING_TAG);
            }
        }
    }
}

/**
 * In an inline element at root level.
 *
 */
class InlineState extends State {

    private $tagName;
    
    function __construct($context, $tagName) {
        parent::__construct($context);
        $this->tagName = $tagName;
    }

    function processToken($token) {
        $this->context->outputText($token['preceding_text']);
        if ($token['tag_name'] !== null) {
            if ($this->context->isOpeningTag($token['tag_name'])) {
                $this->context->setError(Markup::MISSING_CLOSING_TAG);
            } else {
                if ($token['tag_name'] === Markup::TAG_CLOSE_CHAR 
                    . $this->tagName) {
                    $this->outputTag($token);
                    $this->context->popState();
                } else {
                    $this->context->setError(Markup::NESTING_ERROR);
                }
            }
        } else {
            $this->context->setError(Markup::MISSING_CLOSING_TAG);
        }        
    }
}

/**
 * In an inline element inside a block element.
 *
 */
class NestedInlineState extends State {

    private $tagName;

    function __construct($context, $tagName) {
        parent::__construct($context);
        $this->tagName = $tagName;
    }

    function processToken($token) {
        $this->context->outputText($token['preceding_text']);
        if ($token['done']) {
            $this->context->setError(Markup::MISSING_CLOSING_TAG);
        } else {        
            if ($this->context->isOpeningTag($token['tag_name'])) {
                $this->context->setError(Markup::MISSING_CLOSING_TAG);
            } else {
                if ($token['tag_name'] === Markup::TAG_CLOSE_CHAR
                    . $this->tagName) {
                    $this->outputTag($token);
                    $this->context->popState();
                } else {
                    $this->context->setError(Markup::NESTING_ERROR);
                }
            }
        }
    }
}

/**
 * In a block element.
 *
 */
class BlockState extends State {

    private $tagName;

    function __construct($context, $tagName) {
        parent::__construct($context);
        $this->tagName = $tagName;
        $context->setContainsBlockElements();
    }

    function processToken($token) {
        $this->context->outputText($token['preceding_text']);
        if ($token['done']) {
            if ($token['tag_name'] !== null
                && $token['tag_name'] === Markup::TAG_CLOSE_CHAR 
                                          . $this->tagName) {
                $this->outputTag($token);
            } else {
                $this->context->setError(Markup::MISSING_CLOSING_TAG);
            }
        } else {
            if ($this->context->isOpeningTag($token['tag_name'])) {
                if ($this->context->isInlineTag($token['tag_name'])) {
                    $this->outputTag($token);
                    $this->context->pushState(
                            new NestedInlineState($this->context, 
                                                  $token['tag_name']));
                } else {
                    $this->context->setError(Markup::NESTING_ERROR);
                }
            } else {
                if ($token['tag_name'] === Markup::TAG_CLOSE_CHAR
                    . $this->tagName) {
                    $this->outputTag($token);
                    $this->context->popState();
                } else {
                    $this->context->setError(Markup::MISSING_OPENING_TAG);
                }
            }
        }
    }
}

/**
 * A parser for a simple markup language. Text, inline elements and block 
 * elements are allowed at top level. Block elements can contain text and 
 * inline elements but no block elements. Links can be defined using the 
 * syntax "[l=url]link text[/l]".
 * 
 */
class Markup {

    const OK = 0;
    const MISSING_CLOSING_TAG = -1;
    const MISSING_OPENING_TAG = -2;
    const NESTING_ERROR = -3;
    
    const TAG_START_CHAR = '[';
    const TAG_END_CHAR = ']';
    const TAG_CLOSE_CHAR = '/';
    const LINK_SEPARATOR_CHAR = '=';
    const NOMARKUP_OPEN = 'nomarkup';
    const NOMARKUP_CLOSE = '/nomarkup';
    
    private $openingTags = array('p', 'w', 'r', 'i', 'l', self::NOMARKUP_OPEN);
    private $closingTags = array('/p', '/w', '/r', '/i', '/l', 
                                 self::NOMARKUP_CLOSE);
    private $blockTags = array('p', '/p');
    private $inlineTags = array('w', 'r', 'i', 'l', '/w', '/r', '/i', '/l');
    private $linkTags = array('l', '/l');
    private $htmlTags = array('p' => '<p>',
                              'w' => '<i class="el">',
                              'r' => '<span class="root">',
                              'i' => '<i>',
                              'l' => '<a href="%s">',
                              '/p' => '</p>',
                              '/w' => '</i>',
                              '/r' => '</span>',
                              '/i' => '</i>',
                              '/l' => '</a>');
    
    private $text;
    private $textLen;
    private $htmlText;
    private $state;
    private $pos;
    private $error;
    private $containsBlockElements;
    
    /**
     * Creates a Markup instance and tries to parse the input.
     * 
     * @param (string) $input a string that may contain markup
     */
    function __construct($input) {
        $this->text = $input;
        $this->textLen = strlen($input);
        $this->htmlText = '';
        
        // A stack is needed so that blocks can remember their type.
        $this->states = array(new RootLevelState($this));
        
        $this->pos = 0;
        $this->error = self::OK;
        $this->containsBlockElements = false;
        $this->parse();
    } 

    /**
     * Returns Markup::OK if the input was successfully parsed, an error code 
     * otherwise.
     *  
     */
    function error() {
        return $this->error;
    }
    
    /**
     * Returns the input with markup converted to HMTL. Result is undefined if
     * there were errors.
     * 
     */
    function html() {
        return $this->htmlText;
    }
    
    /**
     * Returns true if the input contained a block element.
     *
     */
    function containsBlockElements() {
        return $this->containsBlockElements;
    }
    
    // Methods used by states
    
    function outputText($text) {
        $this->htmlText .= htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
    }
    
    function outputHtmlTag($tagName) {
        $this->htmlText .= $this->htmlTags[$tagName];
    }
    
    function outputTagWithAttr($tagName, $attributes) {
        $val = htmlspecialchars($attributes['href'], ENT_NOQUOTES, 'UTF-8');
        $this->htmlText .= sprintf($this->htmlTags[$tagName], $val);   
    }
    
    function pushState($newState) {
        $this->states[] = $newState;
    }
    
    function popState() {
        array_pop($this->states);
    }
    
    function isOpeningTag($tagName) {
        return in_array($tagName, $this->openingTags);
    }
    
    function isClosingTag($tagName) {
        return in_array($tagName, $this->closingTags);
    }
    
    function isBlockTag($tagName) {
        return in_array($tagName, $this->blockTags);
    }
    
    function isInlineTag($tagName) {
        return in_array($tagName, $this->inlineTags);
    }
    
    function isLinkTag($tagName) {
        return in_array($tagName, $this->linkTags);
    }
    
    function setError($errorCode) {
        $this->error = $errorCode;        
    }
    
    function setContainsBlockElements() {
        $this->containsBlockElements = true;
    }
    
    // Private methods
    
    /**
     * Extracts tokens and passes them to the state machine until end of string
     * is reached or an error occurs.
     * 
     */
    private function parse() {
        $token = null;
        do {
            $token = $this->getNextToken();
            $this->states[count($this->states) - 1]->processToken($token);
            if ($this->error() !== self::OK) {
                $token['done'] = true;
            }
        } while (!$token['done']);
    }
    
    /**
     * Gets the next token and updates $this->pos.
     * 
     * @return the token, see State::processToken for definition
     */
    private function getNextToken() {
        $token = array();
        $from = $this->pos;
        while (($start = $this->findTagStartChar($this->text, 
                                                 $this->textLen, 
                                                 $from)) !== false) {               
            $tagInfo = $this->getTag($this->text, $this->textLen, $start);
            if ($tagInfo !== null 
                && $tagInfo['tag_name'] === self::NOMARKUP_OPEN) {
                // Beginning of "nomarkup" found, ignore all markup until
                // closing tag
                // 
                $endOfNoMarkup = $this->findEndOfNoMarkup($this->text, 
                                                          $this->textLen, 
                                                          $from);
                if ($endOfNoMarkup === false) {
                    // Missing end tag = nomarkup continues to the end of the
                    // string (not a parse error)
                    //
                    $from = $this->textLen - 1;
                } else {
                    $from = $endOfNoMarkup;
                }
            } elseif ($tagInfo !== null) {
                $end = $tagInfo['end'];
                $precedingTextlen = $start - $this->pos;
                $token['preceding_text'] = substr($this->text, $this->pos, 
                                                  $precedingTextlen);
                $token['tag_name'] = $tagInfo['tag_name'];
                $token['attributes'] = $tagInfo['attributes'];
                $this->pos = $end + 1;
                $token['done'] = ($this->pos >= $this->textLen);
                $token = $this->removeNoMarkupTags($token);
                return $token;
            } else {
                $from += 1;
            }
        }
        
        // End of string reached, return tagless token
        $token['done'] = true;
        $token['tag_name'] = null;
        $token['attributes'] = null;
        $token['preceding_text'] = substr($this->text, $this->pos);
        $token = $this->removeNoMarkupTags($token);
        return $token;
    }
    
    /**
     * Looks for a tag in the given position and returns it if found.
     * 
     * @param (string) $str input string
     * @param (string) $strLen length of $str
     * @param (integer) $start position of tag start bracket
     * @return an array with keys "attributes" (mapping from attribute names to
     * values or null), "end" (position of tag end bracket), "tag_name" (tag
     * without start and end brackets, e.g. "/w"). Null if no tag begins at
     * this position.
     */
    private function getTag($str, $strLen, $start) {
        $tags = array_merge($this->openingTags, $this->closingTags);
        foreach ($tags as $tag) {
            $len = strlen($tag) + 1;
            if ($this->isLinkTag($tag) && $this->isOpeningTag($tag)) {
                $endPos = strpos($str, self::TAG_END_CHAR, $start + 1);
                if ($endPos !== false
                    && $this->openingLinkTagStartsHere($str, $strLen, $tag, 
                                                       $start)) {
                    $separatorPos = $start + $len;
                    $uri = substr($str, $separatorPos + 1, 
                                  $endPos - $separatorPos - 1);
                    $tagInfo = array();
                    $tagInfo['attributes'] = array('href' => $uri);
                    $tagInfo['end'] = $endPos;
                    $tagInfo['tag_name'] = $tag;
                    return $tagInfo;
                } 
            } elseif ($this->otherTagStartsHere($str, $strLen, $tag, $start)) {
                $tagInfo = array();
                $tagInfo['attributes'] = null;
                $tagInfo['end'] = $start + $len;
                $tagInfo['tag_name'] = $tag;
                return $tagInfo;
            }
        }
        return null;
    }
    
    /**
     * Finds the next tag start bracket.
     *
     * @param (string) $str input string
     * @param (string) $strLen length of $str
     * @param (integer) $start position to start search from
     * @return position or false if not found
     */
    private function findTagStartChar($str, $strLen, $from) {
        if ($from >= $strLen - 2) {
            return false;
        }
        return strpos($this->text, self::TAG_START_CHAR, $from);
    }
    
    /**
     * Finds the closing tag of a "nomarkup" area.
     *
     * @param (string) $str input string
     * @param (string) $strLen length of $str
     * @param (integer) $start position to start search from
     * @return position of the end of the closing tag or false if not found
     */
    private function findEndOfNoMarkup($str, $strLen, $from) {
        if ($from >= $strLen - 2) {
            return false;
        }
        return strpos($this->text, self::NOMARKUP_CLOSE, $from);
    }
    
    /**
     * Removes all "nomarkup" tags from the text part of a token.
     * 
     * @param (array) $token the token
     * @return (array) the token with tags removed
     */
    private function removeNoMarkupTags($token) {
        $search = array(
            self::TAG_START_CHAR . self::NOMARKUP_OPEN . self::TAG_END_CHAR, 
            self::TAG_START_CHAR . self::NOMARKUP_CLOSE. self::TAG_END_CHAR
        );
        $replace = array('', '');
        $token['preceding_text'] = str_replace($search, 
                                               $replace, 
                                               $token['preceding_text']);
        return $token;    
    }
    
    private function openingLinkTagStartsHere(
        $str, 
        $strLen, 
        $tagName, 
        $start
    ) {
        $restOfTag = $tagName . self::LINK_SEPARATOR_CHAR;
        $tagLen = strlen($restOfTag);
        
        if ($start + $tagLen >= $strLen) {
            return false;
        }
        return substr($str, $start + 1, $tagLen) === $restOfTag; 
    }
    
    private function otherTagStartsHere($str, $strLen, $tagName, $start) {
        $restOfTag = $tagName . self::TAG_END_CHAR;
        $tagLen = strlen($restOfTag);
        
        if ($start + $tagLen >= $strLen) {
            return false;
        }
        return substr($str, $start + 1, $tagLen) === $restOfTag;
    }
}