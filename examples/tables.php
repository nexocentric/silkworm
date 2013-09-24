<?php
////////////////////////////////////////////////////////////////////////////////
// Work     : Hyper Text Silkworm (tables example)
// Copyright: (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)
//                See copywrite at footer for more information.
// Version  : 1.00
////////////////////////////////////////////////////////////////////////////////
require_once("../HyperTextSilkworm.php");

//////////////////////
//start example data->
$table = array( //we'll be recreating this data a number of ways in the examples
	array("column 1", "column 2", "column 3"),
	array("Hyper", "Texting", "Manually"),
	array("Shortcuts", "are", "better..."),
);
//<-end example data
//////////////////////

#-----------------------------------------------------------
# example 1 (completely manual - tedious, but has uses)
#-----------------------------------------------------------
$example1 = new HyperTextSilkworm();
$example1->table(
	"class", "example-table-1",
	$example1->tr(
		array("class"=>"top-row"), //valid attribute syntax
		$example1->th("column 1"),
		$example1->th("column 2"),
		$example1->th("column 3")
	),
	$example1->tr(
		"class", "middle-row", //this is also valid attribute syntax
		$example1->td("Hyper"),
		$example1->td("Texting"),
		$example1->td("Manually")
	),
	$example1->tr(
		"class", "bottom-row", //you can mix
		array("id"=>"table-footer"), //and match both
		$example1->td("Shortcuts"),
		$example1->td("are"),
		$example1->td("better...")
	)
);

#-----------------------------------------------------------
# example 2 (autoTable allows you to use pure PHP arrays)
#-----------------------------------------------------------
$example2 = new HyperTextSilkworm();
$example2->autoTable($table); //that's it we have a table
//however... the formatting isn't there... hold on

#-----------------------------------------------------------
# example 3 (with full attributes)
#-----------------------------------------------------------
$rowAttributes = array( //these are passed as a second 2D array
	array("class"=>"top-row"),
	array("class"=>"middle-row"), //associative
	array("class", "bottom-row") //and regular syntax are valid
);

$example3 = new HyperTextSilkworm();
$example3->autoTable(
	$table, //first array is the table to parse
	true, //this means treat first row as <th>
	"class", "example-table-3", //you can pass attributes as arrays too
	$rowAttributes //
);
//we now have a table that the same as the one above

#-----------------------------------------------------------
# example 4 (nested tables)
#-----------------------------------------------------------
$nestedTable = array(
	array("a", "b", "c"),
	array("d", array(
			array("yes", "we", "can"),
			array("generate", "xD", "ARRAYS!")
		), 
		"f"),
	array("g", "h", array(
			array("aa", "bb", "cc"),
			array("dd", "ee", "ff"),
			array("gg", "hh", "ii")
		)
	)
);
$example4 = new HyperTextSilkworm();
//attibutes can be used just the same as example 3
//for simplicity we'll omit them, but feel free to
//use attributes for xD arrays as well
$example4->autoTable($nestedTable); 

#-----------------------------------------------------------
# example 4 (nested tables)
#-----------------------------------------------------------


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