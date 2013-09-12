<?php

class HtmlInterface 
{
	const INNER_TEXT = 0;
	const ATTRIBUTES = 1;
	const CHILDREN = 2;
	const TAG_NAME = 3;
	const PROPERIES = 4;

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
	private $html = "";
	private $childFragments = array();
	
	public function __construct($parentTag = "")
	{
		$properties = func_get_args();
		array_splice($properties, 0, 1);
		$this->__call($parentTag, $properties);
	}

	//create a function for the html tag then save it
	public function __call($tagName, $properties)
	{
		$attributes = array();
		$innerText = "";
		$stringList = array();
		
		foreach($properties as $property) {
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

		$this->html = $this->createTag($tagName, $attributes, $innerText);
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
		return "";
	}

	public function createTag($tagName, $attributes, $children)
	{
		$createdTag = "";

		//
		if(in_array($tagName, $this->selfClosingTagList)) {
			//
			$createdTag = "<$tagName%s>%s";
		} else {
			//
			$createdTag = "<$tagName%s>%s</$tagName>";
		}

		$createdTag = sprintf(
			"$createdTag\n",
			$this->parseAttributes($attributes),
			$this->parseChildren($children),
			"",
			""
		);

		
		return $createdTag;
	}

	public function __toString()
	{
		return $this->doctype . $this->html;
	}

	# comment tag
	public function comment($comment) {
		echo "\n", $this->indent(), "<!-- $comment -->\n";
		$this->outdent();
	}
}
$something = new HtmlInterface("html", "somting", "yes");