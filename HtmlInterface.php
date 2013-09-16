<?php

class HtmlInterface 
{
	const TOP_TAG_NAME = "TOP_TAG_NAME";
	const PROPERTIES = "PROPERTIES";

	private $inParseCycle = false;
	private $indentLevel = 0;
	private $indentPattern = "\t";
	private $selfClosingTagList = array(
		"base", 
		"basefont", 
		"br", 
		"col", 
		"frame", 
		"hr", 
		"input", 
		"link", 
		"meta", 
		"param"
	);

	//something
	private $doctype = "";
	private $topTag = array();
	private $html = "";
	private $childFragments = array();
	
	public function __construct($definition = "")
	{
		if(!empty($definition)) {
			$this->doctype($definition);
		}
	}

	//create a function for the html tag then save it
	public function __call($tagName, $properties)
	{
		return $this->html = $this->initializeTag($tagName, $properties);
	}
	
	private function initializeTag($tagName, $properties)
	{
		$attributes = array();
		$innerText = "";
		$stringList = array();
		$children = array();
		
		foreach($properties as $property) {
			//since HtmlInterface has a to __toString method
			//this has to be valuated before any
			//string evaluations
			if($property instanceof HtmlInterface) {
				$property->clearDoctype();
				$children[] = $property;
				continue;
			}
			if(strpos($property, "\n") !== false) {
				$children[] = $property;
				continue;
			}
			//inner text and children
			if(is_string($property)) {
				$stringList[] = $property;
				continue;
			}
	    }

	    //
	    $stringCount = count($stringList);
	    if($stringCount % 2) {
			$innerText = $stringList[$stringCount - 1];
	    }
	    
	    for ($nextString = 0; ($nextString + 1) < $stringCount; $nextString += 2) {
	    	$attributes[$stringList[$nextString]] = $stringList[$nextString + 1];
	    }

		return $this->createTag($tagName, $attributes, $innerText, $children);
	}

	public function parseAttributes($attributes)
	{
		$attributeString = "";
		if(empty($attributes)) {
			return $attributeString;
		}
		foreach ($attributes as $name => $value) {
			$attributeString .= " $name=\"$value\""; // there's a space here
		}
		return $attributeString;
	}

	private function increaseIndent($childString)
	{
		$childList = substr_replace(
			$childString,
			"",
			strrpos($childString, "\n")
		);
		$childList = explode("\n", $childList);
		foreach($childList as $index => $child) {
			$childList[$index] = $this->indent() . $child;
		}
		return implode("\n", $childList) . "\n";
		return $childString;
	}
	
	//this function is probably going to cause you lots of problems right now...
	// all fixing needs to happen here
	public function parseChildren($children)
	{
		$childString = "";
		$newline = $this->inParseCycle ? "" : "\n";
		if(empty($children)) {
			return $childString;
		}
		if(!is_array($children)) {
			$children = array($children);
		}
		$currentIndentLevel = $this->indentLevel;
		foreach($children as $child) {
			if($child instanceof HtmlInterface) {
				$this->inParseCycle = true;
				$this->indentLevel = 0;
				$child = $this->parseChildren((string)$child);
				$this->indentLevel = $currentIndentLevel;
				$this->inParseCycle = false;
			}
			$child = $this->increaseIndent($child);
			$childString .= $child;
		}
		return $newline . $childString;
	}

	public function createTag($tagName, $attributes, $innerText = "", $children = "")
	{
		$createdTag = "";
		
		//
		if(in_array($tagName, $this->selfClosingTagList)) {
			//
			if($children) {
				$createdTag = "<$tagName%s>%s%s";
			} else {
				$createdTag = "<$tagName%s>\n%s%s";
			}
			$this->indentLevel = 0;
		} else {
			//
			$createdTag = "<$tagName%s>%s%s</$tagName>\n";
			$this->indentLevel = 1;
		}

		$createdTag = sprintf(
			"$createdTag",
			$this->parseAttributes($attributes),
			$innerText,
			$this->parseChildren($children)
		);
		
		return $createdTag;
	}

	protected function clearDoctype()
	{
		$this->doctype = "";	
	}
	
	public function doctype($definition) {
		$this->doctype = sprintf(
			"<!DOCTYPE %s>\n",
			$definition
		);
	}
	
	public function repeat(HtmlInterface $html, $count)
	{
		$html->clearDoctype();
		return str_repeat($html, $count);
	}
	
	public function newline() {
		return "\n";
	}
	
	private function indent()
	{
		return str_repeat($this->indentPattern, $this->indentLevel);
	}

	public function __toString()
	{
		return $this->doctype . $this->html;
	}

	# comment tag
	public function comment($comment) {
		return "<!-- $comment -->\n";
	}
}

$body = new HtmlInterface("html //definitions");
$body->body();

$html = new HtmlInterface("html //definitions");
$html->html($html->repeat($body, 2));
1 + 1;

class HtmlGen {
  
  static public $self_closing_tags = array(
    "base", "basefont", "br", "col", "frame", "hr", "input", "link", "meta", "param"
  );
  
  static private $indent_level = -1;
  static private $indent_pattern = "\t";
  
  static private $cycles = array();
  
  # cycler
  static public function cycle(Array $options, $handle="default"){
    if(!array_key_exists($handle, self::$cycles)){
      self::$cycles[$handle] = $options;
      return current(self::$cycles[$handle]);
    }
    else {
      if($ret = next(self::$cycles[$handle])){
        return $ret;
      }
      else {
        reset(self::$cycles[$handle]);
        return current(self::$cycles[$handle]);
      }
    }
  }
  
  # reset specific cycle
  static public function reset_cycle($handle="default"){
    if(array_key_exists($handle, self::$cycles)){
      reset(self::$cycles[$handle]);
    }
  }
  
}