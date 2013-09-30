<?php
////////////////////////////////////////////////////////////////////////////////
// Work     : Silkworm (tables example)
// Copyright: (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)
//                See copywrite at footer for more information.
// Version  : 1.00
////////////////////////////////////////////////////////////////////////////////
require_once("../Silkworm.php");

//////////////////////
//start example data->
$table = array( //we'll be recreating this data a number of ways in the examples
	array("column 1", "column 2", "column 3"),
	array("Assisted", "text", "markup"),
	array("definitely", "way", "better!!"),
);
//<-end example data
//////////////////////

#-----------------------------------------------------------
# example 1 (completely manual - tedious, but has uses)
#-----------------------------------------------------------
$example1 = new Silkworm();
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
		$example1->td("Text"),
		$example1->td("markup"),
		$example1->td("manually...")
	),
	$example1->tr(
		"class", "bottom-row", //you can mix
		array("id"=>"table-footer"), //and match both
		$example1->td("assistance"),
		$example1->td("greatly"),
		$example1->td("required...")
	)
);

#-----------------------------------------------------------
# example 2 (autoTable allows you to use pure PHP arrays)
#-----------------------------------------------------------
$example2 = new Silkworm();
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

$example3 = new Silkworm();
$example3->autoTable(
	$table, //first array is the table to parse
	true, //this means treat first row as <th>
	"class", "example-table-3", //you can pass table attributes as arrays too
	$rowAttributes //
);
//we now have a table that the same as the one above

#-----------------------------------------------------------
# example 4 (nested tables)
#-----------------------------------------------------------
$nestedTable = array( //you would probably generate this table programatically
	array("a", "b", "c"),
	array(
		"d", 
		array(
			array("yes", "we", "can"),
			array("generate", "xD", "ARRAYS!")
		), 
		"f"
	),
	array(
		"g", 
		"h", 
		array(
			array("aa", "bb", "cc"),
			array("dd", "ee", "ff"),
			array("gg", "hh", "ii")
		)
	)
);
$example4 = new Silkworm();
//attibutes can be used just the same as example 3
//for simplicity we'll omit them, but feel free to
//use attributes for xD arrays as well
$example4->autoTable($nestedTable, "class", "example-4"); 

#-----------------------------------------------------------
# example 5 (alternating row attributes)
#-----------------------------------------------------------
$longTable = array(
	array("1", "こ", "This"),
	array("2", "の", "feeling"),
	array("3", "気", "that"),
	array("4", "持", "I"),
	array("5", "ち", "have"),
	array("6", "ま", "now"),
	array("7", "さ", "must"),
	array("8", "し", "most"),
	array("9", "く", "definitely"),
	array("10", "愛", "be"),
	array("11", "だ", "love"),
	array("12", "！", "!")
);
$longTableAttributes = array(
	array("class"=>"light-row"), //either syntax works
	#array("class"=>"medium-row"), //uncomment for more fun
	array("class"=>"dark-row") //pick your poison
);

//notice that the attributes
$example5 = new Silkworm();
$example5->autoTable($longTable, $longTableAttributes);

#-----------------------------------------------------------
# example 6 (cell attributes)
#-----------------------------------------------------------
//this last example has been made brief to demonstrate one thing
//cells can also have attributes added to them as needed
$tableCellAttributes = array(
	array(
		"class=light-cell"=>"You better treat me right,", //equal syntax
		"class=>medium-cell"=>"cause I think I'm the king", //associative syntax
		"class, dark-cell"=>"of cellular attributes! (j/k)" //csv syntax
	)
);

#-----------------------------------------------------------
# example 7 (???)
#-----------------------------------------------------------
$forReal = array(array("This", "is", "hot!!!!")); //or not...

#-----------------------------------------------------------
# let's display the results so you can see what happens
#-----------------------------------------------------------
$style = <<<PAGE_STYLES
/* i wish i could use sass here, it would have made it easier */
body {
	margin: 0;
}

div {
	padding: 10px;
	margin: 0;
	display: block;
}

div:nth-child(2n+1) {
	background-image: url(data:image/png;base64,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);
	background-color: #A86C38;
}

div:nth-child(2n+1) p {
	text-shadow: 0px 2px 3px #915D30;
    color: #734822;
    margin: 0 auto;
    font-size: 40px;
}

div:nth-child(2n+2) {
	background-image: url(data:image/png;base64,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);
	background-color: #F2E6D0;
}

div:nth-child(2n+2) p {
	text-shadow: 0px 2px 3px #E6D9C1;
    color: #C2B7A3;
    margin: 0 auto;
    font-size: 40px;
}

table {
	width: 50%;
	margin: 0 auto;
	border-collapse: collapse;
    border: 1px solid black;
    background-color: #C2AE8E;
}

table th {
	border: 1px solid black;
	background-color: #8C4B22;
}

table td {
	border: 1px solid black;
}

.example-4 {
	background-color: #8C4B22;
}

.top-row {
	
}

.middle-row, .bottom-row {
	background-color: ghostwhite;
}

.light-row {
	background-color: ghostwhite;
}

.medium-row {
	background-color: #DBCFB7;
}

.dark-row {
	background-color: #C2AE8E;
}

.light-cell {
	background-color: ghostwhite;	
}

.medium-cell {
	background-color: #C2AE8E;
}

.dark-cell {
	background-color: #8C4B22;
}

div table.hot-pink {
	background-color: #F433ff;
}

PAGE_STYLES;

$head = new Silkworm();
$head->head(
	$head->meta("charset", "UTF-8"),
	$head->title("Silkworm (tables example)"),
	$head->newline(),
	$head->meta("name", "description", "content", "This demostrates how to use tables."),
	$head->meta("name", "viewport", "content", "width=device-width"),
	$head->newline(),
	$head->comment("page styles"),
	$head->style($style)
);

$displayExamples = new Silkworm();
$displayExamples->html(
	$head,
	$displayExamples->body(
		$displayExamples->newline(),
		$displayExamples->comment("example 1"),
		$displayExamples->div(
			$displayExamples->p("Example 1 (basic)"),
			(string)$example1
		),
		$displayExamples->newline(),
		$displayExamples->comment("example 2"),
		$displayExamples->div(
			$displayExamples->p("Example 2 (autoTable no attributes)"),
			(string)$example2
		),
		$displayExamples->newline(),
		$displayExamples->comment("example 3"),
		$displayExamples->div(
			$displayExamples->p("Example 3 (autoTable with attributes)"),
			(string)$example3
		),
		$displayExamples->newline(),
		$displayExamples->comment("example 4"),
		$displayExamples->div(
			$displayExamples->p("Example 4 (nested tables)"),
			(string)$example4
		),
		$displayExamples->newline(),
		$displayExamples->comment("example 5"),
		$displayExamples->div(
			$displayExamples->p("Example 5 (alternating attributes)"),
			(string)$example5 //you can pass a fragement to be added and parsed
		),
		$displayExamples->newline(),
		$displayExamples->comment("example 6"),
		$displayExamples->div(
			$displayExamples->p("Example 6 (cell attributes)"),
			$displayExamples->autoTable($tableCellAttributes) //you can also call direct
		),
		$displayExamples->newline(),
		$displayExamples->comment("example 7???"),
		$displayExamples->div(
			$displayExamples->p("Example 7 (???)"),
			$displayExamples->repeat( //cause you can!!
				$displayExamples->autoTable($forReal, "class", "hot-pink"), //you can also call direct
				rand(1, 40)
			)
		)
	)
);

//let's see how it looks
print((string)$displayExamples);

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