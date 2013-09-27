<?php
require_once(realpath("../Silkworm.php"));

class SilkwormTest extends PHPUnit_Framework_TestCase
{
	const DOUBLE_QUOTE = "\"";

	/** 
	* @test
	*/
	public function toString()
	{
		$html = new Silkworm("html");
		$this->assertTrue(
			is_string((string)$html),
			"Failed to convert interface to string."
		);

	}

	/** 
	* @test
	*/
	public function newline()
	{
		$html = new Silkworm();
		$this->assertSame(
			"\n", 
			$html->newline(), 
			"newline creation failed"
		);
	}

	/** 
	* @test
	*/
	public function comment()
	{
		$html = new Silkworm();
		$this->assertSame(
			"<!-- this is a comment -->\n",
			$html->comment("this is a comment"),
			"Failed to convert interface to string."
		);

	}

	/** 
	* @test
	* @depends toString
	*/
	public function selfClosingTag()
	{
		$html = new Silkworm();
		$html->br();
		$this->assertSame(
			"<br>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends selfClosingTag
	*/
	public function regularTag()
	{
		$html = new Silkworm();
		$html->div();
		$this->assertSame(
			"<div></div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends regularTag
	*/
	public function regularTagWithInnerText()
	{
		$html = new Silkworm();
		$html->div("look here");
		$this->assertSame(
			"<div>look here</div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends regularTag
	*/
	public function returnsDoctype()
	{
		$html = new Silkworm("html //definitions");
		$this->assertSame(
			"<!DOCTYPE html //definitions>\n", 
			(string)$html, 
			"Failed to set solidary !DOCTYPE."
		);

		$html = new Silkworm("html //definitions");
		$html->html();
		$this->assertSame(
			"<!DOCTYPE html //definitions>\n<html></html>\n", 
			(string)$html, 
			"Failed to set !DOCTYPE with document HTML."
		);
	}

	/** 
	* @test
	* @depends regularTag
	*/
	public function tabIndentation()
	{
		$html = new Silkworm();
		$html->html(
			$html->head(),
			$html->body()
		);
		$this->assertSame(
			"<html>\n\t<head></head>\n\t<body></body>\n</html>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends tabIndentation
	*/
	public function spaceIndentation()
	{
		$html = new Silkworm();
		$html->setIndentation(" ");
		$html->html(
			$html->head(),
			$html->body()
		);
		$this->assertSame(
			"<html>\n <head></head>\n <body></body>\n</html>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends tabIndentation
	*/
	public function mixedIndentation()
	{
		$html = new Silkworm();
		$html->setIndentation(" \t");
		$html->html(
			$html->head(),
			$html->body()
		);
		$this->assertSame(
			"<html>\n \t<head></head>\n \t<body></body>\n</html>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends tabIndentation
	*/
	public function reversionToTabIndentation()
	{
		$html = new Silkworm();
		$html->setIndentation("A");
		$html->html(
			$html->head(),
			$html->body()
		);
		$this->assertSame(
			"<html>\n\t<head></head>\n\t<body></body>\n</html>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTag
	*/
	public function selfClosingTagWithAttributes()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->meta("content", "content-text");		
		$this->assertSame(
			"<meta content=${qt}content-text${qt}>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends regularTag
	* @depends selfClosingTagWithAttributes
	*/
	public function regularTagWithAttributes()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->div("class", "classname");
		$this->assertSame(
			"<div class=${qt}classname${qt}></div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithAttributes
	*/
	public function selfClosingTagWithAttributesFromArray()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$html = new Silkworm();
		$attributes["content"] = "content-text";
		$html->meta($attributes);		
		$this->assertSame(
			"<meta content=${qt}content-text${qt}>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends regularTagWithAttributes
	*/
	public function regularTagWithAttributesFromArray()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;

		$html = new Silkworm();
		$attributes["class"] = "classname";
		$html->div($attributes);
		$this->assertSame(
			"<div class=${qt}classname${qt}></div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithAttributes
	*/
	public function selfClosingTagWithBooleanAttributes()
	{
		//declarations
		$qt = SilkwormTest::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->input("type", "checkbox", "checked", "disabled");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} checked disabled>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends regularTagWithInnerText
	* @depends regularTagWithAttributes
	*/
	public function regularTagWithBooleanAttributes()
	{
		$html = new Silkworm();
		$html->button("hidden", "disabled", "click me");
		$this->assertSame(
			"<button hidden disabled>click me</button>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithAttributesFromArray
	* @depends selfClosingTagWithBooleanAttributes
	*/
	public function selfClosingTagWithAttributesFromArrayAndStrings()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$html = new Silkworm();
		$attributes = array("name"=>"frame1", "marginheight"=>"10");
		$html->frame($attributes, "noresize");		
		$this->assertSame(
			"<frame name=${qt}frame1${qt} marginheight=${qt}10${qt} noresize>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends regularTagWithAttributesFromArray
	* @depends regularTagWithBooleanAttributes
	*/
	public function regularTagWithAttributesFromArrayStringsAndInnerText()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$html = new Silkworm();
		$attributes = array("class"=>"classname");
		$html->p($attributes, "disabled", "hidden", "Lorem ipsum dolor sit amet...");
		$this->assertSame(
			"<p class=${qt}classname${qt} disabled hidden>Lorem ipsum dolor sit amet...</p>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTag
	*/
	public function selfClosingTagWithSiblings()
	{
		$html = new Silkworm();
		$html->meta(
			$html->meta()
		);
		$this->assertSame(
			"<meta>\n<meta>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends regularTag
	*/
	public function regularTagWithChildren()
	{
		$html = new Silkworm();
		$html->div(
			$html->span(
				$html->p()
			)
		);
		$this->assertSame(
			"<div>\n\t<span>\n\t\t<p></p>\n\t</span>\n</div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithSiblings
	*/
	public function selfClosingTagWithAttributesAndSiblings()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->meta(
			"content",
			"content-text",
			$html->meta()
		);
		$this->assertSame(
			"<meta content=${qt}content-text${qt}>\n<meta>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	* @depends regularTagWithChildren
	*/
	public function regularTagWithAttributesAndChildren()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->div(
			"class", 
			"classname",
			$html->p("something"),
			$html->p("nothing")
		);
		$this->assertSame(
			"<div class=${qt}classname${qt}>\n\t" .
			"<p>something</p>\n\t" .
			"<p>nothing</p>\n" .
			"</div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends comment
	* @depends newline
	* @depends regularTagWithAttributesAndChildren
	*/
	public function documentFragment()
	{
		$html = new Silkworm();
		$html->div(
			$html->comment("this works"),
			$html->h1(),
			$html->newline(),
			$html->span(
				$html->p(
				)
			)
		);
		$this->assertSame(
			"<div>\n\t<!-- this works -->\n\t<h1></h1>\n\t\n\t<span>\n\t\t<p></p>\n\t</span>\n</div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends regularTagWithAttributesAndChildren
	*/
	public function completeDocument()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		//setup
		$html = new Silkworm("html");
		$html->html(
			$html->head(
				$html->meta("name", "description")
			),
			$html->body(
				$html->p("document")
			)
		);
		$this->assertSame(
			"<!DOCTYPE html>\n" .
			"<html>\n" .
			"\t<head>\n" .
			"\t\t<meta name=${qt}description${qt}>\n" .
			"\t</head>\n" .
			"\t<body>\n" . 
			"\t\t<p>document</p>\n" .
			"\t</body>\n" .
			"</html>\n", 
			(string)$html)
		;
	}

	/**
	* @test
	* @depends returnsDoctype
	* @depends documentFragment
	*/
	public function clearDoctype()
	{
		$body = new Silkworm("html //definitions");
		$body->body();

		$html = new Silkworm("html //definitions");
		$html->html($body);
		$this->assertSame(
			"<!DOCTYPE html //definitions>\n<html>\n\t<body></body>\n</html>\n", 
			(string)$html, 
			"Failed to set !DOCTYPE with document HTML."
		);
	}

	/** 
	* @test
	* @depends clearDoctype
	* @depends documentFragment
	* @depends completeDocument
	*/
	public function completeDocumentFromFragments()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		//setup
		$html = new Silkworm("html");
		$head = new Silkworm();
		$head->head(
				$head->meta("name", "description")
		);
		$body = new Silkworm();
		$body->body(
			$body->p("document")
		);
		$html->html(
			$head,
			$body
		);
		$this->assertSame(
			"<!DOCTYPE html>\n" .
			"<html>\n" .
			"\t<head>\n" .
			"\t\t<meta name=${qt}description${qt}>\n" .
			"\t</head>\n" .
			"\t<body>\n" . 
			"\t\t<p>document</p>\n" .
			"\t</body>\n" .
			"</html>\n", 
			(string)$html
		);
	}

	/** 
	* @test
	* @depends completeDocumentFromFragments
	*/
	public function repeatFragment()
	{
		$div = new Silkworm();
		$div->div(
			$div->div(
				$div->p()
			)
		);
		$html = new Silkworm();
		$html->html(
			$html->repeat($div, 2)
		);
		$this->assertSame(
			"<html>\n" .
			"\t<div>\n" .
			"\t\t<div>\n" .
			"\t\t\t<p></p>\n" .
			"\t\t</div>\n" .
			"\t</div>\n" .
			"\t<div>\n" .
			"\t\t<div>\n" .
			"\t\t\t<p></p>\n" .
			"\t\t</div>\n" .
			"\t</div>\n" .
			"</html>\n", 
			(string)$html
		);
	}

	/** 
	* @test
	* @depends documentFragment
	*/
	public function autoTableThreeByThree()
	{
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table), 
			"failed to create 3 by 3 table."
		);
	}

	/** 
	* @test
	* @depends documentFragment
	* @depends autoTableThreeByThree
	*/
	public function autoTableThreeByThreeAsFragment()
	{
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$parsedTable = new Silkworm();
		$parsedTable->autoTable($table);
		
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			(string)$parsedTable, 
			"failed to create 3 by 3 table."
		);
	}
	
	/** 
	* @test
	* @depends autoTableThreeByThree
	*/
	public function autoTableTriangle()
	{
		$table = array(
			array("a", "b", "c"),
			array("d", "e"),
			array("g")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table), 
			"failed to create a triangle table."
		);
	}

	/** 
	* @test
	* @depends autoTableThreeByThree
	*/
	public function autoTableThreeByThreeWithTableAttributes()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table class=${qt}className${qt} noresize>\n" .
			"\t<tr>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table, "class", "className", "noresize"), 
			"failed to create 3 by 3 table with attributes."
		);
	}

	/** 
	* @test
	* @depends autoTableThreeByThree
	*/
	public function autoTableThreeByThreeWithRowAttributesSame()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr class=${qt}className${qt} noresize>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}className${qt} noresize>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}className${qt} noresize>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table, array(array("class", "className", "noresize"))),
			"failed to create 3 by 3 table with row attributes."
		);
	}

	/** 
	* @test
	* @depends autoTableThreeByThreeWithRowAttributesSame
	*/
	public function autoTableThreeByThreeWithRowAttributesDifferent()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr class=${qt}top${qt}>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}middle${qt}>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}bottom${qt}>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable(
				$table,
				array(
					array("class", "top"), 
					array("class", "middle"), 
					array("class", "bottom")
				)
			), 
			"failed to create 3 by 3 table with different row attributes."
		);
	}
	
	/** 
	* @test
	* @depends autoTableThreeByThreeWithRowAttributesDifferent
	*/
	public function autoTableThreeByThreeWithRowAttributesAlternatingAssociateArray()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr class=${qt}odd${qt}>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}even${qt}>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}odd${qt}>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable(
				$table,
				array(
					array("class"=>"odd"), 
					array("class"=>"even")
				)
			), 
			"failed to create 3 by 3 table with different row attributes."
		);
	}
	
	/** 
	* @test
	* @depends autoTableThreeByThreeWithRowAttributesDifferent
	*/
	public function autoTableThreeByThreeWithRowAttributesAlternatingIndexedArray()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr class=${qt}odd${qt}>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}even${qt}>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr class=${qt}odd${qt}>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable(
				$table,
				array(
					array("class", "odd"), 
					array("class", "even")
				)
			), 
			"failed to create 3 by 3 table with different row attributes."
		);
	}

	/** 
	* @test
	* @depends documentFragment
	*/
	public function autoTableWithCellAttributes()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("class=>left"=>"a", "class=middle"=>"b", "class, right"=>"c")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<td class=${qt}left${qt}>a</td>\n" .
			"\t\t<td class=${qt}middle${qt}>b</td>\n" .
			"\t\t<td class=${qt}right${qt}>c</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table), 
			"failed to create 3 by 3 table."
		);
	}
	
	
	/** 
	* @test
	* @depends autoTableThreeByThree
	*/
	public function autoTableThreeByThreeWithNestedTable()
	{
		$qt = SilkwormTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", array(array("a")))
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<td>a</td>\n" .
			"\t\t<td>b</td>\n" .
			"\t\t<td>c</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>\n" .
			"\t\t\t<table>\n" .
			"\t\t\t\t<tr>\n" .
			"\t\t\t\t\t<td>a</td>\n" .
			"\t\t\t\t</tr>\n" .
			"\t\t\t</table>\n" .
			"\t\t</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable(
				$table
			), 
			"failed to create 3 by 3 table with different row attributes."
		);
	}

	/** 
	* @test
	* @depends documentFragment
	*/
	public function autoTableThreeByThreeWithTableHeaders()
	{
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<th>a</th>\n" .
			"\t\t<th>b</th>\n" .
			"\t\t<th>c</th>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>i</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table, true), 
			"failed to create 3 by 3 table."
		);
	}

	/** 
	* @test
	* @depends autoTableThreeByThreeWithTableHeaders
	*/
	public function autoTableThreeByThreeWithTableHeadersAndNestedTable()
	{
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", array(array("a")))
		);
		$html = new Silkworm();
		$this->assertSame(
			"<table>\n" .
			"\t<tr>\n" .
			"\t\t<th>a</th>\n" .
			"\t\t<th>b</th>\n" .
			"\t\t<th>c</th>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>d</td>\n" .
			"\t\t<td>e</td>\n" .
			"\t\t<td>f</td>\n" .
			"\t</tr>\n" .
			"\t<tr>\n" .
			"\t\t<td>g</td>\n" .
			"\t\t<td>h</td>\n" .
			"\t\t<td>\n" .
			"\t\t\t<table>\n" .
			"\t\t\t\t<tr>\n" .
			"\t\t\t\t\t<td>a</td>\n" .
			"\t\t\t\t</tr>\n" .
			"\t\t\t</table>\n" .
			"\t\t</td>\n" .
			"\t</tr>\n" .
			"</table>\n", 
			$html->autoTable($table, true), 
			"failed to create 3 by 3 table."
		);
	}
}