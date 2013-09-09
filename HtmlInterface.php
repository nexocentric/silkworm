<?php

class HtmlInterface 
{
	const INNER_TEXT = 0;
	const ATTRIBUTES = 1;
	const CHILDREN = 2;
	const TAG_NAME = 3;
	const PROPERIETS = 4;

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
	private $doctype = "";
	private $topTag = "";
	private $indentLevel = -1;
	private $indentationCharacter = "\t";
	private $cycles = array();
	private $html = "";
	private $isChild = false;

	public function __construct($topTag, $innerText="")
	{
		$attributes = func_get_args();
		array_splice($attributes, 0, 2);

		$this->topTag = $this->generateTag(
			$topTag,
			$innerText,
			$attributes
		);
	}

	# the magic
	public function __call($tagName, $properties) {
		//variable declarations
		$propertyArray = array(null, array(), array());
		$stringCount = 0;
		$stringList = array();
		$hasInnerText = false;
		
		//a possible list of attributes
		//inner text and children
		foreach($properties as $property) {
			//inner text and children
			if(strpos($property, "\n") !== false) {
				$propertyArray[HtmlInterface::CHILDREN][] .= $property;
			}
			else {
				$stringList[] = $property;
			}
		}
		
		//count number of possible attributes
		$stringCount = count($stringList);
		//if this is odd last string is inner text
		if($hasInnerText = $stringCount % 2) {
			$propertyArray[HtmlInterface::INNER_TEXT] 
				= $properties[$stringCount - 1];
		}
		
		//parse attributes
		//set the limit for the number of attributes to pair
		$attributeCount = $stringCount - $hasInnerText;
		//pair attributes and save
		for($attributePair = 0; $attributePair < $attributeCount; $attributePair+=2) {
			$propertyArray[HtmlInterface::ATTRIBUTES][$properties[$attributePair]] 
				= $properties[$attributePair + 1];
		}
		
		return $this->generateTag(
			$tagName,
			$propertyArray[HtmlInterface::INNER_TEXT],
			$propertyArray[HtmlInterface::ATTRIBUTES],
			$propertyArray[HtmlInterface::CHILDREN]
		);
	}

	public function __toString()
	{
		return $this->doctype . $this->topTag . $this->html;
	}

	# comment tag
	public function comment($comment) {
		echo "\n", $this->indent(), "<!-- $comment -->\n";
		$this->outdent();
	}

	public function setIndentationCharacter($character = "")
	{
		$this->indentationCharacter = $character;
	}

	public function adjustIndent()
	{
		# set indent level
		/*public function set_indent_level($level){
		if(is_numeric($level)) $this->$indent_level = $level-1;
		}

		# set indent pattern
		public function set_indent_pattern($pattern){
		$this->$indent_pattern = $pattern;
		}

		# increase indent level
		private function indent($increment=true){
		if($increment) $this->$indent_level++;
		$tabs = "";
		for($i=0; $i<$this->$indent_level; $i++) $tabs .= $this->$indent_pattern;
		return $tabs;
		}

		# decrease indent level
		private function outdent(){
		$this->$indent_level--;
		}*/
	}

	public function generateTag($name, $text="", $attributes=array(), $children=array())
	{
		//declarations
		$tag = "";
		$selfClosing = in_array($name, $this->selfClosingTagList);

		//property strings
		$children = $this->concatenateChildren($children);
		$attributes = $this->parseAttributes($attributes);

		//change closing format according to type
		$innerText = $selfClosing ? "" : ">". $text;
		$closing = $selfClosing ? "" : $children . "</" . $name;

		//adjust indentation
		//$tag .= $this->increaseIndent();

		//generate tab
		$tag = sprintf(
			"<%s%s%s%s>\n",
			$name,
			$attributes,
			$innerText,
			$closing
		);
		
		//re-adjust indentation
		//$this->decreaseIndent();
		
		//generated tag
		return $tag;
	}
	
	public function concatenateChildren($children)
	{
		//declarations
		$generatedChildren = "";

		if(empty($children)) {
			return $generatedChildren;
		}
		
		foreach($children as $child) {
			$generatedChildren .= $child;
		}
		
		return $generatedChildren;
	}
	
	public function parseAttributes($attributes)
	{
		//declarations
		$parsedAttributes = "";
		
		if(empty($attributes)) {
			return "";
		}
		
		foreach($attributes as $name => $value) {
			$parsedAttributes = " $name='$value' ";
		}
		return $parsedAttributes;
	}

	public function doctype($attributes = "html")
	{
		$this->doctype = sprintf("<!DOCTYPE %s>\n", $attributes);
	}
}

$htmlInterface = new HtmlInterface("html");
$htmlInterface->doctype();
$string = (string)$htmlInterface->html(
	$htmlInterface->head(
		$htmlInterface->meta("this", "that")
	),
	$htmlInterface->body(
		$htmlInterface->div(
			$htmlInterface->p("Hello World!")
		)
	)
);
1 + 1;