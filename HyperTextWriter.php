<?php
////////////////////////////////////////////////////////////////////////////////
// Work     : Ageha Blue Hyper Text Writer
// Copyright: (c) 2013 Dodzi Dzakuma (http://www.nexocentric.com)
//                See copywrite at footer for more information.
// Version  : 1.00
////////////////////////////////////////////////////////////////////////////////

#===============================================================================
// [author]
// Dodzi Dzakuma
// [summary]
// This is a simple class designed for making it possible to produce
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
// 1) $html = new HyperTextWriter(); //create a new interface
// 2) ... = new HyperTextWriter("html"); //create a new interface with doctype
// 3) $html->doctype("html"); //set the doctype
// 4) $html->indentCharacter(" "); //set indent character as tabs or spaces
// 5) $html->html(); //create a tag !!see README.md for more information
// 6) $html->newline(); //create a newline in HTML document
// 7) $html->comment("comment text"); //create a comment in HTML document
// 8) $html->repeat(HyperTextWriter, int); //repeat a fragment n times
// 9) $html->autoTable(array(array()); //create table from array(array())
#===============================================================================
class HyperTextWriter
{
	/////////////////////////
	//start class constants->
    const DOUBLE_QUOTE = "\"";
    const NEWLINE = "\n";
	const SPACE = " ";
	const TAB = "\t";
	//<-end class constants
	/////////////////////////

	/////////////////////////
	//start class variables->    
	private $parsingHtmlFragment = false;
	private $parsingTableHeader = false;
	private $indentLevel = 0;
	private $indentationPattern = HyperTextWriter::TAB;
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
	private $doctype = "";
	private $html = "";
	//<-end class variables
	/////////////////////////
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Changes the indentation pattern for the document.
	# [parameters]
	# 1) An indentation pattern consisting of tabs or spaces.
	# [return]
	# none
	#-----------------------------------------------------------
	public function setIndentation($indentationPattern = "")
	{
		//declarations
		$space = HyperTextWriter::SPACE;
		$tab = HyperTextWriter::TAB;

		//check to see if use entered invalid tab value
		if(preg_match("/[^$space$tab]/", $indentationPattern) === 0) {
			//indentation pattern is valid, use it
			$this->indentationPattern = $indentationPattern;
			return;
		}
		//trigger_error("Only ASCII spaces and tabs can be used for indentation.");
	}#----------------- setIndentation end -----------------#

	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Create a new HyperTextWriter for use to create HTML.
	# [parameters]
	# 1) The doctype definition of the HTML document to be
	#    created. This is optional. By setting this a doctype
	#    will be added to the document, otherwise the document
	#    will be generated without a doctype.
	# [return]
	# 1) A new HyperTextWriter for use.
	#-----------------------------------------------------------
	public function __construct($definition = "")
	{
	    //check if user wants to set a doctype
		if(!empty($definition)) {
		    //user specified a doctype, so set it
			$this->doctype($definition);
		}
	}#----------------- __construct end -----------------#

	/////////////////////////////////////
	//start magic method implementation->
    #-----------------------------------------------------------
    # [author]
	# Dodzi Dzakuma
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
	}#----------------- __call end -----------------#

	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# This is an overload of the PHP __toString() function. This
	# prints out the generated HTML data as a printable string.
	# [parameters]
	# none
	# [return]
	# The generated HTML as a string.
	#-----------------------------------------------------------
	public function __toString()
	{
		return $this->doctype . $this->html;
	}#----------------- __toString end -----------------#
	//<-end magic method implementation
	/////////////////////////////////////
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# This splits the data passed to it by the __call function
	# into attributes, children and HyperTextWriter fragments and
	# passes this information along with the tag name to the
	# createTag function.
	# [parameters]
	# 1) The name of the tag.
	# 2) Tag properies(attributes, innertext, children/siblings)
	# [return]
	# 1) A tag string created by the createTag function.
	#-----------------------------------------------------------
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
		    //check if this is an HyperTextWriter fragment
			//since HyperTextWriter has a to __toString method
			//this has to be valuated before any
			//string evaluations
			if($property instanceof HyperTextWriter) {
			    //safety for users who set doctypes
			    //on HyperTextWriter fragments
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
					$stringList[] = $attributeName;
					
					//non booleans get paired
					if(!in_array($property, $this->booleanAttributes)) {
						//value is attribute value
						$stringList[] = $value;
					}
				}
				continue;
			}
			
			//if it has a carriage, it's a chlid
			if(strpos($property, HyperTextWriter::NEWLINE) !== false) {
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
	}#----------------- initializeTag end -----------------#
    
    #-----------------------------------------------------------
    # [author]
	# Dodzi Dzakuma
	# [summary]
	# Function for turning the associative attribute array into
	# valid html attributes.
	# [parameters]
	# 1) An associative array of attribute names and values.
	# [return]
	# 1) A string of attributes.
	# 2) If no attributes are set, a blank string is returned.
	#-----------------------------------------------------------
	protected function parseAttributes($attributes)
	{
	    //declarations
	    $space = HyperTextWriter::SPACE;
	    $quote = HyperTextWriter::DOUBLE_QUOTE;
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
				$attributeString .= "$space$name";
				continue;
		    }
			$attributeString .=  "$space$name=$quote$value$quote";
		}
		return $attributeString;
	}#----------------- parseAttributes end -----------------#

    #-----------------------------------------------------------
    # [author]
	# Dodzi Dzakuma
	# [summary]
	# This increases the indentation of nested children.
	# [parameters]
	# 1) A string of child tags.
	# [return]
	# 1) A string of child tags that have had the indentation 
	#    adjusted.
	#-----------------------------------------------------------
	protected function increaseIndent($childString)
    {
    	//declarations
    	$newline = HyperTextWriter::NEWLINE;
    	
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
	}#----------------- increaseIndent end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Parses an array of children by turning them into a string
	# of appropriately indented ones.
	# [parameters]
	# 1) An array of children.
	# [return]
	# 1) A string of parsed children.
	# 2) If no children are present, an empty string.
	#-----------------------------------------------------------
	protected function parseChildren($children)
	{
	    //delcarations
		$childString = "";
		
		//check if children exists
		if(empty($children)) {
		    //return empty string
			return $childString;
		}
		
		//special parse loop for HyperTextWriter fragments
		//the top line of a fragment doesn't have a carriage
		//before it, so add it here
		$newline = $this->parsingHtmlFragment ? "" : HyperTextWriter::NEWLINE;
		
		//the children for this tag are either
		//a) a single string that missed array formatting
		//b) an HyperTextWriter fragment
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
			if($child instanceof HyperTextWriter) {
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
	}#----------------- parseChildren end -----------------#
    
    #-----------------------------------------------------------
    # [author]
	# Dodzi Dzakuma
	# [summary]
	# Creates a tag of with the specified parameters.
	# [parameters]
	# 1) HTML tag name.
	# 2) Tag attributes.
	# 3) Inner text.
	# 4) Children as strings or HTML fragments.
	# [return]
	# 1) The generated tag.
	#-----------------------------------------------------------
	protected function createTag($tagName, $attributes, $innerText = "", $children = "")
	{
		//declarations
		$createdTag = "";
		$newline = HyperTextWriter::NEWLINE;
		
		//change sprintf statment for 
		//regular and self closing tags
		if(in_array($tagName, $this->selfClosingTagList)) {
			//self closing
			$newline = $children ? "" : $newline; //newline if no children
			$createdTag = "<$tagName%s>$newline%s%s";
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
	}#----------------- createTag end -----------------#

    #-----------------------------------------------------------
    # [author]
	# Dodzi Dzakuma
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
	#-----------------------------------------------------------
	protected function clearDoctype()
	{
		$this->doctype = "";	
	}#----------------- clearDoctype end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Sets the !DOCTYPE for this HTML document.
	# [parameters]
	# 1) !DOCTYPE definition
	# [return]
	# none
	#-----------------------------------------------------------
	public function doctype($definition) {
		$this->doctype = sprintf(
			"<!DOCTYPE %s>" . HyperTextWriter::NEWLINE,
			$definition
		);
	}#----------------- doctype end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Repeats an HyperTextWriter fragment n number of times.
	# [parameters]
	# 1) HyperTextWriter fragment.
	# 2) The number of times to repeat the fragment.
	# [return]
	# 1) A string of children repeated n number of times.
	#-----------------------------------------------------------
	public function repeat(HyperTextWriter $html, $count)
	{
		$html->clearDoctype();
		return str_repeat($html, $count);
	}#----------------- repeat end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Adds a newline to the document for viusal purposes only.
	# [parameters]
	# none
	# [return]
	# none
	#-----------------------------------------------------------
	public function newline() {
		return HyperTextWriter::NEWLINE;
	}#----------------- newline end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
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
	#-----------------------------------------------------------
	protected function indent()
	{
		return str_repeat($this->indentationPattern, $this->indentLevel);
	}#----------------- indent end -----------------#

	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Adds an HTML style comment to the document.
	# [parameters]
	# 1) Comment contents.
	# [return]
	# 1) A comment for display.
	#-----------------------------------------------------------
	public function comment($comment)
	{
		return "<!-- $comment -->" . HyperTextWriter::NEWLINE;
	}#----------------- comment end -----------------#

	//////////////////////////////
	//start auto table functions->
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
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
	#-----------------------------------------------------------
	private function parseCells($array)
	{
		//declarations
		$cells = "";
		$cellType = $this->parsingTableHeader ? "th" : "td";
		
		//decide whether to parse cells or rows
		foreach($array as $cell) {
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
			
			//regular cell parse
			$cells .= $this->initializeTag(
				$cellType,
				array($cell)
			);
		}
		
		return $cells;
	}#----------------- parseCells end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
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
	#-----------------------------------------------------------
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
				foreach($differentAttributes[$differentArrayMarker] as $attribute) {
		            $rowAttributes[] = $attribute;
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
	}#----------------- parseRows end -----------------#
	
	#-----------------------------------------------------------
	# [author]
	# Dodzi Dzakuma
	# [summary]
	# Creates an HTML table based on the arrays passed.
	# [parameters]
	# 1) Multidimensional array to parse.
	# 2) Optional array of row attributes. (can be xDimensional)
	# 3) Optional table attributes. (x number of strings)
	# 4) Boolean to designate use of the th tag for first row.
	# [return]
	# 1) An HTML table.
	#-----------------------------------------------------------
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
					if(is_array($rowAttribute)) {
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
		$table = $this->initializeTag(
			"table",
			$tableAttributes
		);
		
		return $table;
	}#----------------- comment end -----------------#
	//<-end auto table functions
	//////////////////////////////
}#==================== HyperTextWriter end ====================#

////////////////////////////////////////////////////////////////////////////////
// The MIT License (MIT)
// 
// Copyright (c) 2013 Dodzi Dzakuma (http://www.nexocentric.com)
// 
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
////////////////////////////////////////////////////////////////////////////////