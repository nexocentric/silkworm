<?php
////////////////////////////////////////////////////////////////////////////////
// Work     : (ml)Silkworm (basic example)
// Copyright: (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)
//                See copyright at footer for more information.
// Version  : 1.00
////////////////////////////////////////////////////////////////////////////////
require_once("../Silkworm.php");
require_once("./modular.php");

#-----------------------------------------------------------
# basic example (brute force???)
#-----------------------------------------------------------
$example1 = new Silkworm();
$example1->doctype("html");
$example1->html(
	$example1->head(
		$example1->comment("start standard header block"),
		$example1->meta("charset", "UTF-8"),
		$example1->title("Silkworm (basic example)"),
		$example1->newline(),
		$example1->meta("name", "description", "content", "This is just a quick example on how to get started."),
		$example1->meta("name", "viewport", "content", "width=device-width"),
		$example1->comment("end standard header block"),
		$example1->newline(),
		$example1->comment("a stylesheet for some flair?"),
		$example1->comment("there's a better way to do this, please see the next tutorial"),
		$example1->style(
			""
		)
	),
	$example1->body(
		$example1->div(
			$example1->div(
				"class", "content",
				$example1->p("This is a short and sweet example to demonstrate Silkworm usage.")
			),
			$example1->newline(),
			$example1->table( 
				$example1->tr(
					"class", "table-header",
					$example1->th("Example 1"),
					$example1->th("Example 2"),
					$example1->th("Example 3")
				),
				$example1->tr(
					$example1->td(
						"Example 1 shows you that you can write out everything " .
						"directly as a set of tags if you like. If you want to make " .
						"a standard template that you plug every thing into, this " .
						"is one method that you might want to employ."
					),
					$example1->td(
						"Example 2 shows that you can break everything up into " .
						"more manageable parts, that you can then toss into Silkworm " .
						"for some quick formatting and parsing before you display it. " .
						"This allows you to divide web page creation into parts that " .
						"can be managed by classes that you define yourself. " .
						"This also introduces the autoTable() function which " .
						"accepts a multidimensional array and turns it into an " .
						"HTML table."
					),
					$example1->td(
						"Example 3 is available to show that this library is fairly " .
						"Dynamic and the only thing holding you back is creativity."
					)
				)
			)
		)
	)
);

#-----------------------------------------------------------
# organized example (easier to work with)
#-----------------------------------------------------------
$style = "";
//the table that we did in example 1
$text1 = <<<TEXT1
Example 1 shows you that you can write out everything directly 
as a set of tags if you like. If you want to make a standard 
template that you plug every thing into, this is one method 
that you might want to employ.
TEXT1;

$text2 = <<<TEXT2
Example 2 shows that you can break everything up into  
more manageable parts, that you can then toss into Silkworm  
for some quick formatting and parsing before you display it  
This allows you to divide web page creation into parts that  
can be managed by classes that you define yourself  
This also introduces the autoTable() function which  
accepts a multidimensional array and turns it into an  
HTML table.
TEXT2;

$text3 = <<<TEXT3
Example 3 is available to show that this library is fairly  
Dynamic and the only thing holding you back is creativity
TEXT3;

$table = array(
	array("Example 1", "Example 2", "Example 3"),
	array($text1, $text2, $text3)
);
$rowAttributes = array(
	array("class", "table-header"),
	//array("", "") //see what happens when you uncomment this line
);

//this is for our head block
$head = new Silkworm();
$head->head(
	$head->comment("start standard header block"),
	$head->meta("charset", "UTF-8"),
	$head->title("Silkworm (quick start example)"),
	$head->newline(),
	$head->meta("name", "description", "content", "This is just a quick example on how to get started."),
	$head->meta("name", "viewport", "content", "width=device-width"),
	$head->comment("end standard header block"),
	$head->newline(),
	$head->comment("a stylesheet for some flair?"),
	$head->comment("just passing a parameter makes it easier"),
	$head->style($style)
);

//this is for the body
$body = new Silkworm();

$divAttributes = array("class"=>"content");
$body->body(
	$body->div(
		$body->div(
			$divAttributes, //you can pass attributes as arrays too
			$body->p("This is a short and sweet example to demonstrate Silkworm's usage.")
		),
		$body->newline(),
		$body->autoTable(
			$table,
			$rowAttributes
		)
	)
);

//you can also define a doctype this way if you like
$example2 = new Silkworm();
$example2->doctype("HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\"");
$example2->html($head, $body);

#-----------------------------------------------------------
# advanced example (probably the way that you might use it)
#-----------------------------------------------------------
//for a deeper understanding of how this works, please see
//the modular.php for more information
$example3 = new Webpage();

#-----------------------------------------------------------
# remove the comment mark from an example and see how it works :)
#-----------------------------------------------------------
#print((string)$example1);
#print((string)$example2);
#print($example3->display());

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