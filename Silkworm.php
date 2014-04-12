<?php
#═══════════════════════════════════════════════════════════════════════════════
# [work]
# (ml)Silkworm
# [version]
# 2.00
# [copyright]
# (c) 2014 Dodzi Y. Dzakuma (http://www.nexocentric.com)
# See LICENSE file for copyright information.
# [summary]
# This is a snippet generation library for HTML or XML document generation.
#═══════════════════════════════════════════════════════════════════════════════

#───────────────────────────────────────────────────────────────────────────────
# [author]
# Dodzi Y. Dzakuma
# [summary]
# This is a simple class designed for making it possible to produce
# html tags and documents using PHP. This interfaces always produces
# systematically indented HTML code to ease the reading and visual debugging
# of HTML documents.
#
# This interface has no external dependencies and can be used by including it
# in a PHP file and creating an instance of the class.
# [usage]
# This is a list of public methods. Please see the README for more detailed
# documentation on usage.
#
# 1) $html = new Silkworm() //create a new interface
# 2) ... = new Silkworm("html") //create a new interface with doctype
# 6) setSilkwormAlias("") //name to access the class
# 8) $html->html() //create a tag !!see README.md for more information
# 3) adjustIndentation($markupText, $indentLevel) // change final indentation
# 8) doctype($definition) //set the doctype
# 9) repeat(Silkworm, int) //repeat a fragment n times
# 10) newline() //create a newline in HTML document
# 11) comment($comment) //create a comment in HTML document
# 12) stringWithDocumentHeader($data)
# 14) setIndentation($indentationPattern = "") //combinations of spaces and tabs
# 15) setAdjustedIndentation($string) //adjust last minute indentation
# 16) defineSelfClosingTags() //define additional self closing tags via a list
# 17) setSelfClosingTagStyle($style) //<img> vs <img />
# 18) defineBooleanAttributes() //define additional boolean attributes
# 13) setBooleanDisplayStyle($style = "") //a vs a="a" vs a="true"
# 19) xmlVersion($version) //set xml version
# 20) cdata($cdata) //add a cdata element
# 21) autoTable(array(array())) //create table from array(array())
#───────────────────────────────────────────────────────────────────────────────
class Silkworm implements ArrayAccess
{
	#-----------------------------------------------------------
	# class constants
	#-----------------------------------------------------------
	const FORWARD_SLASH = "/";
	const DOUBLE_QUOTE = "\"";
	const NEWLINE = PHP_EOL;
	const SPACE = " ";
	const TAB = "\t";
	const BOOLEAN_ATTRIBUTES_MAXIMIZED = "MAXIMIZED";
	const BOOLEAN_ATTRIBUTES_MINIMIZED = "MINIMIZED";
	const BOOLEAN_ATTRIBUTES_BOOLEAN = "BOOLEAN";
	const DEFAULT_ZERO_PADDING_LENGTH = 10;

	#-----------------------------------------------------------
	# class variables
	#-----------------------------------------------------------
	private $booleanAttributeDisplayStyle = Silkworm::BOOLEAN_ATTRIBUTES_MAXIMIZED;
	private $selfClosingTagStyle = "";
	private $cocoons = array(); //processed silkworms saved for use later
	private $cocoonCount = 0; //number of numerically indexed cocoons
	private $parsingHtmlFragment = false;
	private $parsingTableHeader = false;
	private $indentLevel = 0;
	private $adjustedIndentLevel = 0;
	private $zeroPaddingLength = Silkworm::DEFAULT_ZERO_PADDING_LENGTH;
	private $indentationPattern = Silkworm::TAB;
	private $selfClosingTags = array(
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
	private $booleanAttributes = array(
		"allowfullscreen",
		"async",
		"autofocus",
		"checked",
		"compact",
		"declare",
		"default",
		"defer",
		"disabled",
		"formnovalidate",
		"hidden",
		"inert",
		"ismap",
		"itemscope",
		"multiple",
		"multiple",
		"muted",
		"nohref",
		"noresize",
		"noshade",
		"novalidate",
		"nowrap",
		"open",
		"readonly",
		"required",
		"reversed",
		"seamless",
		"selected",
		"sortable",
		"truespeed",
		"typemustmatch"
	);
	private $xmlVersion = "";
	private $doctype = "";
	private $html = "";

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Create a new Silkworm for use to create HTML.
	# [parameters]
	# 1) A string representing if this is an XML document.
	# [return]
	# 1) A new Silkworm for use.
	#===========================================================
	public function __construct($documentSpecifier = "")
	{
		if (stripos($documentSpecifier, "x") !== false) {
			$this->setSelfClosingTagStyle("XML");
		}

		$this->zeroPaddingLength = $this->calculateZeroPaddingLength();
	}

	/////////////////////////////////////
	//start magic method implementation->
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP __call function. This 
	# passes the name of an HTML tag to be generated along with
	# the attributes to be set to the initializeTag function.
	# [parameters]
	# 1) The name of the tag.
	# 2) Tag properies(attributes, innertext, children/siblings)
	# [return]
	# 1) The tag generated by the initializeTag function.
	#===========================================================
	public function __call($tagName, $properties)
	{
		//parse the tag properties and generate tag of $tagName
		return $this->html = $this->initializeTag($tagName, $properties);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Adjusts the overall indentation of the markup text to 
	# output if the adjustedIndentLevel variable has been set.
	# This can also be used stand alone in which case an indent
	# level can be set manually.
	# [parameters]
	# 1) the string form of the markup text to indent
	# 2) an indent level (optional)
	# [return]
	# 1) the markup text with its indentatinon adjusted 
	#    (if specified)
	#===========================================================
	public function adjustIndentation($markupText, $indentLevel = null)
	{
		#---------------------------------------
		# from string parse same whitespace
		#---------------------------------------
		if ($this->adjustedIndentLevel == 0) {
			return $markupText;
		}

		#---------------------------------------
		# from string parse same whitespace
		#---------------------------------------
		$newline = Silkworm::NEWLINE;
		$adjustedIndentation = str_repeat(
			$this->indentationPattern, 
			is_null($indentLevel) ? $this->adjustedIndentLevel : $indentLevel
		);

		#---------------------------------------
		# from string parse same whitespace
		#---------------------------------------
		$adjustedMarkupText = explode($newline, $markupText);

		#---------------------------------------
		# from string parse same whitespace
		#---------------------------------------
		$currentLine = 0;
		$lineCount = count($adjustedMarkupText);
		foreach ($adjustedMarkupText as &$line) {
			$currentLine++;
			if ($lineCount == $currentLine) {
				continue;
			}

			$line = $adjustedIndentation . $line;
		}

		#---------------------------------------
		# from string parse same whitespace
		#---------------------------------------
		return implode($newline, $adjustedMarkupText);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP __toString() function. This
	# prints out the generated HTML data as a printable string.
	# [parameters]
	# none
	# [return]
	# The generated HTML as a string.
	#===========================================================
	public function __toString()
	{

		$classCocoons = $this->cocoons;
		$cocoons = "";
		if(!empty($classCocoons)) {
			foreach($classCocoons as $key => $silkworm) {
				$classCocoons["$key"] = $silkworm;
			}
			ksort($classCocoons, 5);
			foreach($classCocoons as $silkworm) {
				$cocoons .= $silkworm;
			}
			return $cocoons;
		}

		$this->html = $this->adjustIndentation($this->html);			

		return $this->xmlVersion . $this->doctype . $this->html;
	}
	//<-end magic method implementation
	/////////////////////////////////////

	/////////////////////////////////////
	//start array access implementation->
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Uses PHP_INT_MAX to determine the number of digits used
	# for padding array index integers.
	# [parameters]
	# none
	# [return]
	# 1) the number of digits to use for padding integers
	#===========================================================
	private function calculateZeroPaddingLength()
	{
		$maxDigits = PHP_INT_MAX;
		$maxDigits = str_split($maxDigits);
		return count($maxDigits);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Determines if the string for an array index is a number,
	# then left pads the number with zeros if is a pure integer.
	# [parameters]
	# 1) a string to evaluate
	# [return]
	# 1) the original or padded string
	#===========================================================
	private function zeroPadArrayIndex($string)
	{
		if (strpos($string, ".") !== false) {
			return $string;
		}

		if (!is_int($string)) {
			return $string;
		}

		$zeroPaddingLength = $this->zeroPaddingLength;
		$string = sprintf("%0${zeroPaddingLength}s", $string);
		return (string)$string;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP offsetExists() function. Checks
	# if HTML fragment has beend saved or not.
	# [parameters]
	# 1) Fragment name.
	# [return]
	# 1) Boolean showing if fragment exists.
	#===========================================================
	public function offsetExists($fragmentName)
	{
		$fragmentName = $this->zeroPadArrayIndex($fragmentName);
		return isset($this->cocoons[$fragmentName]);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP offsetGet() function. Gets the 
	# saved fragment by name if exists.
	# [parameters]
	# 1) Fragment name.
	# [return]
	# 1) The fragment if exists.
	# 2) Empty if fragment doesn't exist.
	#===========================================================
	public function offsetGet($fragmentName)
	{
		$fragmentName = $this->zeroPadArrayIndex($fragmentName);
		if($this->offsetExists($fragmentName)) {
			return $this->cocoons[$fragmentName];
		}
		return;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP offsetSet() function. Saves an
	# HTML fragment to the class by name. Saves by index if no
	# name has been specified.
	# [parameters]
	# 1) The fragment name.
	# 2) The fragment itself.
	# [return]
	# none
	#===========================================================
	public function offsetSet($fragmentName, $fragment)
	{
		if(empty($fragmentName)) {
			$fragmentName = $this->cocoonCount;
			$this->cocoonCount++;
		}
		$fragmentName = $this->zeroPadArrayIndex($fragmentName);
		$this->cocoons[$fragmentName] = $fragment;
		$this->html = "";
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP offsetUnset() function. Unsets
	# a fragment by its name if the fragment exists.
	# [parameters]
	# 1) The fragrment name to unset.
	# [return]
	# none
	#===========================================================
	public function offsetUnset($fragmentName)
	{
		$fragmentName = $this->zeroPadArrayIndex($fragmentName);
		if(isset($this->cocoons[$fragmentName])) {
			unset($this->cocoons[$fragmentName]);
		}
	}
	//<-end array access implementation
	/////////////////////////////////////

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Parses a list of user defined parameters and adds it to
	# the overall class list by matching the function name of
	# the calling class to an array in this class.
	# [parameters]
	# 1) the function name to use in finding an array
	# 2) a variable list of arguments that to be parsed and
	#    added to a class list (see implementation below)
	# [return]
	# none
	#===========================================================
	private function addUserDefinedParameter($functionName)
	{
		#---------------------------------------
		# initalizations
		#---------------------------------------
		$space = Silkworm::SPACE;
		$classParameterArray = null;
		$userDefinedParameters = array();
		$parameters = func_get_args();
		# adjust the parameter list
		$parameters = $parameters[1]; //the first parameter is $functionName

		#which operation are we doing
		if (stripos($functionName, "booleanAttributes")) {
			$classParameterArray = &$this->booleanAttributes;
		} else if (stripos($functionName, "selfClosingTags")) {
			$classParameterArray = &$this->selfClosingTags;
		} else {
			return;
		}

		#---------------------------------------
		# run through and parse all attributes
		# passed to this method
		#---------------------------------------
		foreach ($parameters as $attribute) {
			#---------------------------------------
			# user defined attributes as an array
			#---------------------------------------
			if (is_array($attribute)) {
				# flatten the array for safety
				$attribute = new RecursiveIteratorIterator(
					new RecursiveArrayIterator($attribute)
				);

				#add the list to the list of parsed attibutes
				$userDefinedParameters = array_merge(
					$userDefinedParameters,
					iterator_to_array($attribute)
				);
				continue;
			}

			# remove spaces to save parsing troubles
			$attribute = str_replace($space, "", $attribute);

			#---------------------------------------
			# check to see if this is a comma
			# delimited list
			#---------------------------------------
			$attributeLastCharacter = strlen($attribute) - 1;
			if (
				strpos($attribute, ",") !== false && 
				strpos($attribute, ",", $attributeLastCharacter) === false && 
				strpos($attribute, "${space}", $attributeLastCharacter) === false 
			) {
				#---------------------------------------
				# add the parsed values to the list
				#---------------------------------------
				array_merge(
					$userDefinedParameters = array_merge(
						$userDefinedParameters,
						explode(",", $attribute)
					)
				);
				continue;
			}

			# just a single value add it to the list
			$userDefinedParameters[] = $attribute;
		}

		#---------------------------------------
		# add these to the global list
		#---------------------------------------
		$classParameterArray = array_merge(
			$classParameterArray,
			$userDefinedParameters
		);
	}
	
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# This splits the data passed to it by the __call function
	# into attributes, children and Silkworm fragments and
	# passes this information along with the tag name to the
	# createTag function.
	# [parameters]
	# 1) The name of the tag.
	# 2) Tag properies(attributes, innertext, children/siblings)
	# [return]
	# 1) A tag string created by the createTag function.
	#===========================================================
	protected function initializeTag($tagName, $properties)
	{
		//declarations
		$attributes = array();
		$innerText = "";
		$stringList = array(); //work storage for strings passed to this function
		$children = array();

		//go through the complete property list and
		//divide it by property type
		foreach($properties as $property) {
			//check if this is an Silkworm fragment
			//since Silkworm has a to __toString method
			//this has to be valuated before any
			//string evaluations
			if($property instanceof Silkworm) {
				//safety for users who set doctypes
				//on Silkworm fragments
				$property->clearDoctype();

				//treat the fragment as a child
				$children[] = $property;
				continue;
			}

			//these are probably matched attributes
			//this check is done before the strpos check
			//because arrays break strpos
			if(is_array($property)) {
				foreach($property as $attributeName => $value) {
					//key is attribute name
					$stringList[] = htmlspecialchars($attributeName);

					//non booleans get paired
					if(!in_array($property, $this->booleanAttributes)) {
						//value is attribute value
						$stringList[] = htmlspecialchars($value);
					}
				}
				continue;
			}

			//if it has a carriage, it's a chlid
			if(strpos($property, Silkworm::NEWLINE) !== false) {
				$children[] = $property;
				continue;
			}

			//these are either attributes or inner text
			if(is_string($property)) {
				//save for later and check for inner text
				$stringList[] = htmlspecialchars($property);
				continue;
			}
		}

		//set up boolean attributes for parsing
		$stringCount = count($stringList);
		for ($nextString = $stringCount; $nextString > 0; $nextString--) {
			//going backwards through the list because of array slice
			$property = $stringList[$nextString - 1];
			if(in_array($property, $this->booleanAttributes)) {
				//pair the boolean with a blank value
				//for parsing purposes only
				array_splice($stringList, $nextString, 0, "");
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

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Function for turning the associative attribute array into
	# valid html attributes.
	# [parameters]
	# 1) An associative array of attribute names and values.
	# [return]
	# 1) A string of attributes.
	# 2) If no attributes are set, a blank string is returned.
	#===========================================================
	protected function parseAttributes($attributes)
	{
		//declarations
		$space = Silkworm::SPACE;
		$quote = Silkworm::DOUBLE_QUOTE;
		$attributeString = ""; //parsed string of attributes

		//check if attributes where set
		if(empty($attributes)) {
			//always return a blank string
			return $attributeString;
		}

		//go through the array of attributes
		//and pair them accordingly
		foreach ($attributes as $name => $value) {
			//check if the attribute is a boolean value
			if(in_array($name, $this->booleanAttributes)) {
				switch($this->booleanAttributeDisplayStyle) {
					case Silkworm::BOOLEAN_ATTRIBUTES_MAXIMIZED:
						$attributeString .= "$space$name=$quote$name$quote";
						break;
					case Silkworm::BOOLEAN_ATTRIBUTES_BOOLEAN:
						$attributeString .= "$space$name=${quote}true${quote}";
						break;
					default:
						$attributeString .= "$space$name";
						break;
				}
				continue;
			}
			$attributeString .=  "$space$name=$quote$value$quote";
		}
		return $attributeString;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# This increases the indentation of nested children.
	# [parameters]
	# 1) A string of child tags.
	# [return]
	# 1) A string of child tags that have had the indentation 
	#    adjusted.
	#===========================================================
	protected function increaseIndent($childString)
	{
		//declarations
		$newline = Silkworm::NEWLINE;

		//remove the final carriage because
		//if it's there explode will treat
		//it as an empty string
		$childList = substr_replace(
			$childString,
			"",
			strrpos($childString, $newline)
		);

		//split the children into their respective lines
		$childList = explode($newline, $childList);

		//go through each and adjust the indentation
		foreach($childList as $index => $child) {
			// they have to stay at their original index
			// for order
			$childList[$index] = $this->indent() . $child;
		}

		//glue together with carriages and add the final
		//one as well
		return implode($newline, $childList) . $newline;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Parses an array of children by turning them into a string
	# of appropriately indented ones.
	# [parameters]
	# 1) An array of children.
	# [return]
	# 1) A string of parsed children.
	# 2) If no children are present, an empty string.
	#===========================================================
	protected function parseChildren($children)
	{
		//declarations
		$childString = "";

		//check if children exists
		if(empty($children)) {
			//return empty string
			return $childString;
		}

		//special parse loop for Silkworm fragments
		//the top line of a fragment doesn't have a carriage
		//before it, so add it here
		$newline = $this->parsingHtmlFragment ? "" : Silkworm::NEWLINE;

		//the children for this tag are either
		//a) a single string that missed array formatting
		//b) an Silkworm fragment
		if(!is_array($children)) {
			//convert the children to an array
			//for parsing
			$children = array($children);
		}

		//save the indent level just incase changes
		//must be made
		$currentIndentLevel = $this->indentLevel;

		//adjust the indentation of childrent to be nested
		foreach($children as $child) {
			//check if this is an html fragment
			if($child instanceof Silkworm) {
				$this->parsingHtmlFragment = true; //start fragment parsing

				//the fragment is already indented
				//so don't indent while parsing it to a string
				$this->indentLevel = 0;
				$child = $this->parseChildren((string)$child);
				$this->indentLevel = $currentIndentLevel;

				$this->parsingHtmlFragment = false; //end fragment parsing
			}
			//properly indent children
			$child = $this->increaseIndent($child);
			$childString .= $child;
		}
		return $newline . $childString;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Creates a tag of with the specified parameters.
	# [parameters]
	# 1) HTML tag name.
	# 2) Tag attributes.
	# 3) Inner text.
	# 4) Children as strings or HTML fragments.
	# [return]
	# 1) The generated tag.
	#===========================================================
	protected function createTag($tagName, $attributes, $innerText = "", $children = "")
	{
		//declarations
		$createdTag = "";
		$newline = Silkworm::NEWLINE;

		//change sprintf statment for 
		//regular and self closing tags
		if(in_array($tagName, $this->selfClosingTags)) {
			//self closing
			$newline = $children ? "" : $newline; //newline if no children
			$createdTag = "<$tagName%s{$this->selfClosingTagStyle}>$newline%s%s";
			$this->indentLevel = 0;
		} else {
			//regular tag
			$createdTag = "<$tagName%s>%s%s</$tagName>$newline";
			$this->indentLevel = 1;
		}

		//create the tag
		$createdTag = sprintf(
			"$createdTag",
			$this->parseAttributes($attributes),
			$innerText,
			$this->parseChildren($children)
		);

		return $createdTag;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Removes !DOCTYPE from children if the user accidentally
	# set the !DOCTYPE on HTML fragments.
	#
	# !!NOTICE!!
	# For internal use only.
	# [parameters]
	# none
	# [return]
	# none
	#===========================================================
	protected function clearDoctype()
	{
		$this->doctype = "";	
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Sets the !DOCTYPE for this HTML document.
	# [parameters]
	# 1) !DOCTYPE definition
	# [return]
	# none
	#===========================================================
	public function doctype($definition) {
		//declarations
		$replacementCount = 1;
		$extraAttributes = func_get_args();

		//initializations
		array_splice($extraAttributes, 0, 1);
		$extraAttributes = $this->initializeTag(
			"br",
			$extraAttributes
		);

		//adjust the attributes for insertion into this tag
		//this leaves the space after the br tag intact so it doesn't
		//hurt formatting
		$extraAttributes = str_replace("<br", "", $extraAttributes, $replacementCount);
		$extraAttributes = preg_replace("/(\/*>{1}[\r\n]*)/", "", $extraAttributes);

		$this->doctype = sprintf(
			"<!DOCTYPE %s%s>" . Silkworm::NEWLINE,
			$definition,
			$extraAttributes
		);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Repeats an Silkworm fragment n number of times.
	# [parameters]
	# 1) Html fragment.
	# 2) The number of times to repeat the fragment.
	# [return]
	# 1) A string of children repeated n number of times.
	#===========================================================
	public function repeat($html, $count)
	{
		if ($html instanceof Silkworm) {
			$html->clearDoctype();
		}
		return str_repeat($html, $count);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Adds a newline to the document for viusal purposes only.
	# [parameters]
	# none
	# [return]
	# none
	#===========================================================
	public function newline() {
		return Silkworm::NEWLINE;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Function for controlling the indentation pattern of this
	# document.
	#
	# !!NOTICE!!
	# For internal use only.
	# [parameters]
	# none
	# [return]
	# An indentation pattern.
	#===========================================================
	protected function indent()
	{
		return str_repeat($this->indentationPattern, $this->indentLevel);
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Adds an HTML style comment to the document.
	# [parameters]
	# 1) Comment contents.
	# [return]
	# 1) A comment for display.
	#===========================================================
	public function comment($comment)
	{
		return "<!-- $comment -->" . Silkworm::NEWLINE;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Creates a string of the passed structure with doctype and
	# or XML version appended to it.
	# [parameters]
	# 1) A new structure.
	# [return]
	# 1) A string representing the structure with doctype and 
	#    XML version appened to it.
	#===========================================================
	public function stringWithDocumentHeader($data)
	{
		return $this->xmlVersion . $this->doctype . $data;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Changes the indentation pattern for the document.
	# [parameters]
	# 1) An indentation pattern consisting of tabs or spaces.
	# [return]
	# none
	#===========================================================
	public function setIndentation($indentationPattern = "")
	{
		//declarations
		$space = Silkworm::SPACE;
		$tab = Silkworm::TAB;

		//check to see if use entered invalid tab value
		if(preg_match("/[^$space$tab]/", $indentationPattern) === 0) {
			//indentation pattern is valid, use it
			$this->indentationPattern = $indentationPattern;
			return;
		}
		//trigger_error("Only ASCII spaces and tabs can be used for indentation.");
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Implementation of the PHP offsetUnset() function. Unsets
	# a fragment by its name if the fragment exists.
	# [parameters]
	# 1) The fragrment name to unset.
	# [return]
	# none
	#===========================================================
	public function setAdjustedIndentation($string)
	{
		#---------------------------------------
		# the user is setting the indent via int
		#---------------------------------------
		if (is_int($string)) {
			$this->adjustedIndentLevel = $string;
			return;
		}

		#---------------------------------------
		# use a string supplied as a method for
		# parsing adjusting the curent indent
		# level by counting the whitespace
		# characters until the first non white-
		# space character
		#---------------------------------------
		$string = str_split($string); //prepare for analysis
		foreach ($string as $marker) {
			# check if accepted whitespace character
			if (!in_array($marker, array(Silkworm::SPACE, Silkworm::TAB))) {
				break;
			}
			$this->adjustedIndentLevel++;
		}
	}
	
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# A method to allow the user to set which parameters will be
	# parsed as self-closing tags.
	# [parameters]
	# 1) a variable list of parameters can be passed to this
	#    function if they follow the following format
	#    a) multiple variables ("a", "b", "c") 
	#    b) a comma delimited string ("a, b, c")
	#    c) an array of values (array("a", "b", "c"))
	#    the formats can each be handed together to the function
	# [return]
	# none
	#===========================================================
	public function defineSelfClosingTags()
	{
		$this->addUserDefinedParameter(__FUNCTION__, func_get_args());
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Changes the self-closing tag style.
	# a) HTML = <img>
	# b) XML  = <img />
	# [parameters]
	# 1) The style to be used ([X]ML / [H]TML)
	# [return]
	# none
	#===========================================================
	public function setSelfClosingTagStyle($style)
	{
		//declarations
		$space = Silkworm::SPACE;
		$forwardSlash = Silkworm::FORWARD_SLASH;

		if (stripos($style, "x") !== false) {
			$this->selfClosingTagStyle = "$space$forwardSlash";
		} else {
			$this->selfClosingTagStyle = "";
		}
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# A method to allow the user to set which parameters will be
	# parsed as boolean attributes.
	# [parameters]
	# 1) a variable list of parameters can be passed to this
	#    function if they follow the following format
	#    a) multiple variables ("a", "b", "c") 
	#    b) a comma delimited string ("a, b, c")
	#    c) an array of values (array("a", "b", "c"))
	#    the formats can each be handed together to the function
	# [return]
	# none
	#===========================================================
	public function defineBooleanAttributes()
	{
		$this->addUserDefinedParameter(__FUNCTION__, func_get_args());
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Changes the display style for boolean attributes.
	# [parameters]
	# 1) Case insensitive style name.
	#   ([ma]ximized, [mi]nimized, [bo]olean)
	#
	# !NOTICE!
	# The default for this is to set the display style to
	# minimized.
	# [return]
	# none
	#===========================================================
	public function setBooleanDisplayStyle($style = "")
	{
		if (stripos($style,"ma") !== false) {
			$this->booleanAttributeDisplayStyle = Silkworm::BOOLEAN_ATTRIBUTES_MAXIMIZED;
		} elseif (stripos($style,"bo") !== false) {
			$this->booleanAttributeDisplayStyle = Silkworm::BOOLEAN_ATTRIBUTES_BOOLEAN;
		} else {
			$this->booleanAttributeDisplayStyle = Silkworm::BOOLEAN_ATTRIBUTES_MINIMIZED;
		}
	}
	
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Creates an alias for the Silkworm class.
	# [parameters]
	# 1) New name to access Silkworm by.
	# [return]
	# 1) The return value of the class_alias method.
	#===========================================================
	public static function setSilkwormAlias($name)
	{
		return class_alias("Silkworm", $name);
	}

	///////////////////////
	//start xml functions->
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Sets the XML version of the document.
	# [parameters]
	# 1) A real number representing the XML version.
	# 2) An variable amount of attributes for the tag.
	# [return]
	# none
	#===========================================================
	public function xmlVersion($version)
	{
		//declarations
		$replacementCount = 1;
		$xmlVersionTag = "";
		$extraAttributes = func_get_args();

		//initializations
		$extraAttributes[0] = array("version"=>"$version");
		$xmlVersionTag = $this->initializeTag(
			"br",
			$extraAttributes
		);

		//adjust the tag
		$xmlVersionTag = str_replace("<br", "<?xml", $xmlVersionTag, $replacementCount);
		$xmlVersionTag = preg_replace("/(\s*\/*>{1})/", "?>", $xmlVersionTag);

		$this->xmlVersion = $xmlVersionTag;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Creates a CDATA tag to be added to the tree.
	# [parameters]
	# 1) The CDATA to be added.
	# [return]
	# 1) A CDATA tag to be added to the tree.
	#===========================================================
	public function cdata($cdata)
	{
		return "<![CDATA[$cdata]]>" . Silkworm::NEWLINE;
	}
	//<-end xml functions
	///////////////////////

	//////////////////////////////
	//start auto table functions->
	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Makes a cell tag for every element in the array passed
	# as its parameter.
	# 
	# !!NOTICE!!
	# If the array passed to this function is multidimensional
	# the function will treat it as a nested table. This makes a
	# call to the autoTable function to start the process again.
	# [parameters]
	# 1) A single dimenion array to parse.
	# [return]
	# 1) A string of parsed cells.
	#===========================================================
	private function parseCells($array)
	{
		//declarations
		$cells = "";
		$cellType = $this->parsingTableHeader ? "th" : "td";
		$properties = array();

		//decide whether to parse cells or rows
		foreach($array as $attributes => $cell) {
			$properties = array();
			//check to make sure this is a single
			//dimensional array
			if(is_array($cell)) {
				//treat this as a nested table
				$cells .= $this->initializeTag(
					"td",
					array($this->autoTable($cell))
				);
				continue;
			}

			if (!is_numeric($attributes)) {
				if (strpos($attributes, "=>") !== false) {
					$attributes = explode("=>", $attributes);
					$properties[] = array($attributes[0]=>$attributes[1]);
				} elseif (strpos($attributes, "=") !== false) {
					$attributes = explode("=", $attributes);
					$properties[] = array($attributes[0]=>$attributes[1]);
				} else {
					$attributes = explode(",", $attributes);
					$properties[] = array(
						trim($attributes[0]) =>
						trim($attributes[1])
					);
				}
			} else {
				$attributes = "";
			}

			$properties[] = $cell;

			//regular cell parse
			$cells .= $this->initializeTag(
				$cellType,
				#array($cell, $attributes)
				$properties
			);
		}

		return $cells;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Makes a table row for every array contained in the array
	# passed to the function as a parameter.
	#
	# !NOTICE!
	# If a flat array is given for the properties, all rows will
	# have the properties applied to them. If a multidimensioned
	# array is passed, the properties in the array will
	# alternate rows.
	# [parameters]
	# 1) A multidimensional array.
	# 2) Row attributes.
	# [return]
	# 1) A string of rows and attributes.
	#===========================================================
	private function parseRows($array, $properties)
	{
		//declarations
		$rows = "";
		$rowAttributes = array();
		$differentAttributes = array();

		//walkthrough and parse row properties
		foreach($properties as $property) {
			//this is a flat array
			if(is_string($property)) {
				$rowAttributes[] = $property;
			}
			//alternating properties passed
			if(is_array($property)) {
				$differentAttributes[] = $property;
			}
		}

		//this parses properties and adds it
		//and creates new rows
		$differentArrayMarker = 0;
		$differentArrayCount = count($differentAttributes);
		foreach($array as $row) {
			//check to see if alternating properties passed
			if($differentAttributes) {
				//make sure that we have an array of strings
				foreach($differentAttributes[$differentArrayMarker] as $attribute => $value) {
					if(is_array($value)) {
						$rowAttributes[] = $value;
						continue;
					}
					$rowAttributes[] = $attribute;
					$rowAttributes[] = $value;
				}

				//alternate the properties using this marker
				if(($differentArrayMarker + 1) == $differentArrayCount) {
					$differentArrayMarker = 0;		
				} else {
					$differentArrayMarker++;
				}
			}

			//add the parsed cells to the row attributes
			$rowAttributes["cells"] = $this->parseCells($row);
			//finished parsing table header if already set
			$this->parsingTableHeader = false;

			//tag created
			$rows .= $this->initializeTag(
				"tr",
				$rowAttributes
			);
		}

		return $rows;
	}

	#===========================================================
	# [author]
	# Dodzi Y. Dzakuma
	# [summary]
	# Creates an HTML table based on the arrays passed.
	# [parameters]
	# 1) Multidimensional array to parse.
	# 2) Optional array of row attributes. (can be xDimensional)
	# 3) Optional table attributes. (x number of strings)
	# 4) Boolean to designate use of the th tag for first row.
	# [return]
	# 1) An HTML table.
	#===========================================================
	public function autoTable($array)
	{
		//declarations
		$table = "";
		$tableAttributes = array();
		$rowAttributes = array();

		//initializations
		$properties = func_get_args();
		array_splice($properties, 0, 1); //array that the table is to parse

		//separate attributes
		foreach($properties as $property) {
			//check for row attributes
			if(is_array($property)) {
				//separate row attributes
				foreach($property as $value) {
					if(is_array($value)) {
						$rowAttributes[] = $value;
						continue;
					}
					//flat attributes
					$tableAttributes[] = $value;
				}
			}
			if(is_bool($property)) {
				$this->parsingTableHeader = $property;
			}
			//table attributes
			if(is_string($property)) {
				$tableAttributes[] = $property;
			}
		}

		//add parsed rows to table attributes
		$tableAttributes[] = $this->parseRows($array, $rowAttributes);

		//table created
		$table = $this->__call(
			"table",
			$tableAttributes
		);

		return $table;
	}
	//<-end auto table functions
	//////////////////////////////
}
#═══════════════════════════════════════════════════════════════════════════════
# 
#═══════════════════════════════════════════════════════════════════════════════