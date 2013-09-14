<?php

class HtmlInterface 
{
	const TOP_TAG_NAME = "TOP_TAG_NAME";
	const PROPERTIES = "PROPERTIES";

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
	
	public function __construct($parentTag)
	{
		//initializations
		$this->topTag[HtmlInterface::TOP_TAG_NAME] = "";
		$this->topTag[HtmlInterface::PROPERTIES] = array();
		
		$properties = func_get_args();
		array_splice($properties, 0, 1);
		$this->topTag[HtmlInterface::TOP_TAG_NAME] = $parentTag;
		$this->topTag[HtmlInterface::PROPERTIES] = $properties;
	}

	//create a function for the html tag then save it
	public function __call($tagName, $properties)
	{
		$this->html = $this->something($tagName, $properties);
	}
	
	private function something($tagName, $properties)
	{
		$attributes = array();
		$innerText = "";
		$stringList = array();
		$children = array();
		
		foreach($properties as $property) {
			if(strpos($property, "\n") !== false) {
				$children[] = $property;
				continue;
			}
			//inner text and children
			if(is_string($property)) {
				$stringList[] = $property;
			}
	    }

	    //
	    $stringCount = count($stringList);
	    for ($nextString = 0; $nextString <= $stringCount; $nextString += 2) {
	    	if($nextString == $stringCount) {
	    		//$innerText = $stringList[$nextString];
	    		break;
	    	}
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

	public function parseChildren($children)
	{
		$childString = "";
		if(empty($children)) {
			return $childString;
		}
		foreach($children as $child) {
			$childString .= $child;
		}
		return $childString;
	}

	public function createTag($tagName, $attributes, $innerText = "", $children = "")
	{
		$createdTag = "";

		//
		if(in_array($tagName, $this->selfClosingTagList)) {
			//
			$createdTag = "<$tagName%s>\n%s%s";
		} else {
			//
			$createdTag = "<$tagName%s>%s%s</$tagName>\n";
		}

		$createdTag = sprintf(
			"$createdTag",
			$this->parseAttributes($attributes),
			$innerText,
			$this->parseChildren($children)
		);

		
		return $createdTag;
	}

	public function doctype($definition) {
		$this->doctype = sprintf(
			"<!DOCTYPE %s>\n",
			$definition
		);
	}

	public function __toString()
	{
		if(!empty($this->html)) {
			$this->topTag[HtmlInterface::PROPERTIES][] = $this->html;
		}
		$html = $this->something(
			$this->topTag[HtmlInterface::TOP_TAG_NAME],
			$this->topTag[HtmlInterface::PROPERTIES]
		);
		return $this->doctype . $html;
	}

	# comment tag
	public function comment($comment) {
		echo "\n", $this->indent(), "<!-- $comment -->\n";
		$this->outdent();
	}
}

$tag = new HtmlInterface("meta");
$tag->meta();
$something = (string)$tag;