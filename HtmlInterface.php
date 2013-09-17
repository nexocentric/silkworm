<?php
#===============================================================================
// [summary]
// This is a simple interface designed for making it possible to produce
// html tags and documents using PHP. This interfaces always produces
// systematically indented HTML code to ease the reading and visual debugging
// of HTML documents.
//
// This interface has no external dependencies and can be used by including it
// in a PHP file and creating an instance of the class.
// [usage]
// This is a list of public methods. Please see the README.md for more detailed
// documentation on usage.
//
// 1) $html = new HtmlInterface(); // create a new interface
// 2) ... = new HtmlINterface("html"); // create a new interface with doctype
// 3) $html->doctype("html"); // set the doctype
// 4) $html->indentCharacter(" "); // set indent character as tabs or spaces
// 5) $html->html(); // create a tag !!see README.md for more information
// 6) $html->newline(); //create a newline in HTML document
// 7) $html->comment("comment text"); //create a comment in HTML document
// 8) $html->repeat(HtmlInterface, int); //repeat a fragment n times
#===============================================================================
class HtmlInterface 
{
    //
	const TOP_TAG_NAME = "TOP_TAG_NAME";
	const PROPERTIES = "PROPERTIES";

    
	private $inParseCycle = false;
	private $indentLevel = 0;
	private $indentPattern = "\t"; //change it so that the indent
	//pattern can be changed
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
	
	#-----------------------------------------------------------
	# [summary]
	# Create a new HtmlInterface for use to create HTML.
	# [parameters]
	# 1) The doctype definition of the HTML document to be
	#    created. This is optional. By setting this a doctype
	#    will be added to the document, otherwise the document
	#    will be generated without a doctype.
	# [return]
	# 1) A new HtmlInterface for use.
	#-----------------------------------------------------------
	public function __construct($definition = "")
	{
	    //check if user wants to set a doctype
		if(!empty($definition)) {
		    //user specified a doctype, so set it
			$this->doctype($definition);
		}
	}

    #-----------------------------------------------------------
	# [summary]
	# This is an overload of the PHP __call function. This 
	# passes the name of an HTML tag to be generated along with
	# the attributes to be set to the initializeTag function.
	# [parameters]
	# 1) The name of the tag.
	# 2) Tag properies(attributes, innertext, children/siblings)
	# [return]
	# 1) The tag generated by the initializeTag function.
	#-----------------------------------------------------------
	public function __call($tagName, $properties)
	{
	    //parse the tag properties and generate tag of $tagName
		return $this->html = $this->initializeTag($tagName, $properties);
	}
	
	#-----------------------------------------------------------
	# [summary]
	# This splits the data passed to it by the __call function
	# into attributes, children and HtmlInterface fragments and
	# passes this information along with the tag name to the
	# createTag function.
	# [parameters]
	# 1) The name of the tag.
	# 2) Tag properies(attributes, innertext, children/siblings)
	# [return]
	# 1) A tag string created by the createTag function.
	#-----------------------------------------------------------
	private function initializeTag($tagName, $properties)
	{
	    //declarations
		$attributes = array();
		$innerText = "";
		$stringList = array(); //work storage for strings passed to this function
		$children = array();
		
		//go through the complete property list and
		//divide it by property type
		foreach($properties as $property) {
		    //check if this is an HtmlInterface fragment
			//since HtmlInterface has a to __toString method
			//this has to be valuated before any
			//string evaluations
			if($property instanceof HtmlInterface) {
			    //safety for users who set doctypes
			    //on HtmlInterface fragments
				$property->clearDoctype();
				
				//treat the fragment as a child
				$children[] = $property;
				continue;
			}
			//if it has a carriage, it's a chlid
			if(strpos($property, "\n") !== false) {
				$children[] = $property;
				continue;
			}
			//these are either attributes or inner text
			if(is_string($property)) {
			    //save for later and check for inner text
				$stringList[] = $property;
				continue;
			}
	    }

	    //begin analyzing the string list
	    $stringCount = count($stringList);
	    //if has a remainder this contains inner text
	    if($stringCount % 2) {
	        //inner text is always last string in list
			$innerText = $stringList[$stringCount - 1];
	    }
	    
	    //pair the rest of the strings as attributes
	    for ($nextString = 0; ($nextString + 1) < $stringCount; $nextString += 2) {
	        //attribute name = attribute value
	        //this makes it easier for the create tag function
	    	$attributes[$stringList[$nextString]] = $stringList[$nextString + 1];
	    }
        
        //create a tag and return it
		return $this->createTag($tagName, $attributes, $innerText, $children);
	}
    
    #-----------------------------------------------------------
	# [summary]
	# Function for turning the associative attribute array into
	# valid html attributes.
	# [parameters]
	# 1) An associative array of attribute names and values.
	# [return]
	# 1) A string of attributes.
	# 2) If no attributes are set, a blank string is returned.
	#-----------------------------------------------------------
	private function parseAttributes($attributes)
	{
	    //declarations
		$attributeString = ""; //parsed string of attributes
		
		//check if attributes where set
		if(empty($attributes)) {
		    //always return a blank string
			return $attributeString;
		}
		foreach ($attributes as $name => $value) {
		    //!!! you can make another array here
		    //!!! for attributes that just require
		    //!!! the name eg. "checked"
		    //!!! this is the same as the check for self
		    //!!! closing tags
			$attributeString .= " $name=\"$value\""; // there's a space here
			//!! change the space to a class constant
		}
		return $attributeString;
	}

    #-----------------------------------------------------------
	# [summary]
	# This increases the indentation of nested children.
	# [parameters]
	# 1) A string of child tags.
	# [return]
	# 1) A string of child tags that have had the indentation 
	#    adjusted.
	#-----------------------------------------------------------
	private function increaseIndent($childString)
    {
        //remove the final carriage because
        //if it's there explode will treat
        //it as an empty string
		$childList = substr_replace(
			$childString,
			"",
			strrpos($childString, "\n")
		);
		//split the children into their respective lines
		$childList = explode("\n", $childList);
		
		//go through each and adjust the indentation
		foreach($childList as $index => $child) {
		    // they have to stay at their original index
		    // for order
			$childList[$index] = $this->indent() . $child;
		}
		//glue together with carriages and add the final
		//one as well
		return implode("\n", $childList) . "\n";
	}
	
	#-----------------------------------------------------------
	# [summary]
	# Parses an array of children by turning them into a string
	# of appropriately indented ones.
	# [parameters]
	# 1) An array of children.
	# [return]
	# 1) A string of parsed children.
	# 2) If no children are present, an empty string.
	#-----------------------------------------------------------
	//this function is probably going to cause you lots of problems right now...
	// all fixing needs to happen here!!!!!
	public function parseChildren($children)
	{
	    //delcarations
		$childString = "";
		
		//check if children exists
		if(empty($children)) {
		    //return empty string
			return $childString;
		}
		
		//special parse loop for HtmlInterface fragments
		//the top line of a fragment doesn't have a carriage
		//before it, so add it here
		$newline = $this->inParseCycle ? "" : "\n";
		
		//the children for this tag are either
		//a) a single string that missed array formatting
		//b) an HtmlInterface fragment
		if(!is_array($children)) {
		    //convert the children to an array
		    //for parsing
			$children = array($children);
		}
		
		//save the indent level just incase changes
		//must be made
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
    
    #-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
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

    #-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	protected function clearDoctype()
	{
		$this->doctype = "";	
	}
	
	#-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	public function doctype($definition) {
		$this->doctype = sprintf(
			"<!DOCTYPE %s>\n",
			$definition
		);
	}
	
	#-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	public function repeat(HtmlInterface $html, $count)
	{
		$html->clearDoctype();
		return str_repeat($html, $count);
	}
	
	#-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	public function newline() {
		return "\n";
	}
	
	#-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	private function indent()
	{
		return str_repeat($this->indentPattern, $this->indentLevel);
	}
    
    #-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	public function __toString()
	{
		return $this->doctype . $this->html;
	}

	#-----------------------------------------------------------
	# [summary]
	# none
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	public function comment($comment) {
		return "<!-- $comment -->\n";
	}
}#==================== HtmlInterface end ====================#