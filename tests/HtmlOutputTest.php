<?php
require_once(realpath("../HtmlInterface.php"));

class HtmlOutputTest extends PHPUnit_Framework_TestCase
{
	public $htmlInterface = null;

	public static function setUpBeforeClass()
	{
		
	}

	public function setUp()
	{
		$this->htmlInterface = new HtmlInterface("html");
	}

	/** 
	* @test
	*/
	public function isHtmlInterface()
	{
		$this->assertTrue(
			($this->htmlInterface instanceof HtmlInterface), 
			"Failed to assert that this is an instance of HtmlIntrface."
		);
	}

	/** 
    * @test
    */
	public function returnsParentTag()
	{
		//setup
		$html = new HtmlInterface("html");
		$head = new HtmlInterface("head");
		$body = new HtmlInterface("body");
		$div = new HtmlInterface("div");
		$br = new HtmlInterface("br");

		$this->assertSame(
			"<html></html>\n",
			(string)$html,
			"Failed to return html tag as parent."
		);

		$this->assertSame(
			"<head></head>\n",
			(string)$head,
			"Failed to return head tag as parent."
		);

		$this->assertSame(
			"<body></body>\n",
			(string)$body,
			"Failed to return body tag as parent."
		);

		$this->assertSame(
			"<div></div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);

		$this->assertSame(
			"<br />\n",
			(string)$br,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
	* @test
	*/
	public function returnsDoctype()
	{
		$html = new HtmlInterface("html");
		$html->doctype(
			"html"
		);

		$this->assertSame(
			"<!DOCTYPE html>\n<html></html>\n", 
			(string)$html, 
			"Failed to convert produce valid doctype."
		);
	}

	/** 
	* @test
	*/
	public function toString()
	{
		//create the most basic set of tags
		$this->htmlInterface->html(
			$this->htmlInterface->head(),
			$this->htmlInterface->body()
		);

		//test the generated tags
		$this->assertSame(
			"<html>\n\t<head>\n\t</head>\n\t<body></body>\n\t</html>", 
			(string)$this->htmlInterface,
			"The string value from the actual test doesn't match the expected."
		);
	}
}
