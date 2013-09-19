<?php
require_once(realpath("../HtmlInterface.php"));

class HtmlOutputTest extends PHPUnit_Framework_TestCase
{
	const DOUBLE_QUOTE = "\"";

	/** 
	* @test
	*/
	public function toString()
	{
		$html = new HtmlInterface("html");
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface("html //definitions");
		$this->assertSame(
			"<!DOCTYPE html //definitions>\n", 
			(string)$html, 
			"Failed to set solidary !DOCTYPE."
		);

		$html = new HtmlInterface("html //definitions");
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
		$html->meta("content", "content-text");		
		$this->assertSame(
			"<meta content=\"content-text\">\n",
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
		$html = new HtmlInterface();
		$html->div("class", "classname");
		$this->assertSame(
			"<div class=\"classname\"></div>\n",
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
		$qt = HtmlOutputTest::DOUBLE_QUOTE;

		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
		$html->button("hidden", "disabled", "click me");
		$this->assertSame(
			"<button hidden disabled>click me</button>\n",
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
		$html->meta(
			"content",
			"content-text",
			$html->meta()
		);
		$this->assertSame(
			"<meta content=\"content-text\">\n<meta>\n",
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
		$html = new HtmlInterface();
		$html->div(
			"class", 
			"classname",
			$html->p("something"),
			$html->p("nothing")
		);
		$this->assertSame(
			"<div class=\"classname\">\n\t" .
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
		$html = new HtmlInterface();
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
		//setup
		$html = new HtmlInterface("html");
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
			"\t\t<meta name=\"description\">\n" .
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
		$body = new HtmlInterface("html //definitions");
		$body->body();

		$html = new HtmlInterface("html //definitions");
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
		//setup
		$html = new HtmlInterface("html");
		$head = new HtmlInterface();
		$head->head(
				$head->meta("name", "description")
		);
		$body = new HtmlInterface();
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
			"\t\t<meta name=\"description\">\n" .
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
		$div = new HtmlInterface();
		$div->div(
			$div->div(
				$div->p()
			)
		);
		$html = new HtmlInterface();
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
		$html = new HtmlInterface();
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
    * @depends autoTableThreeByThree
    */
	public function autoTableTriangle()
	{
		$table = array(
			array("a", "b", "c"),
			array("d", "e"),
			array("g")
		);
		$html = new HtmlInterface();
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
		$qt = HtmlOutputTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new HtmlInterface();
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
			$html->autoTable($table, "class", "classname", "noresize"), 
			"failed to create 3 by 3 table."
		);
	}

	/** 
    * @test
    * @depends autoTableThreeByThree
    */
	public function autoTableThreeByThreeWithRowAttributesSame()
	{
		$qt = HtmlOutputTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new HtmlInterface();
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
			$html->autoTable($table, "class", "classname", "noresize"), 
			"failed to create 3 by 3 table."
		);
	}

	/** 
    * @test
    * @depends autoTableThreeByThreeWithRowAttributesSame
    */
	public function autoTableThreeByThreeWithRowAttributesDifferent()
	{
		$qt = HtmlOutputTest::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new HtmlInterface();
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
			$html->autoTable($table, "class", "classname", "noresize"), 
			"failed to create 3 by 3 table."
		);
	}
}
