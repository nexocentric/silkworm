<?php
require_once(realpath("../HtmlInterface.php"));

class HtmlOutputTest extends PHPUnit_Framework_TestCase
{
	/** 
    * @test
    */
	public function selfClosingTag()
	{
		$br = new HtmlInterface("br");
		$this->assertSame(
			"<br>\n",
			(string)$br,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
    * @test
    * @depends selfClosingTag
    */
	public function regularTag()
	{
		$div = new HtmlInterface("div");
		$this->assertSame(
			"<div></div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);
	}

	/** 
    * @test
    * @depends selfClosingTag
    */
	public function selfClosingTagWithAttributes()
	{
		$meta = new HtmlInterface("meta", "content", "content-text");
		$this->assertSame(
			"<meta content=\"content-text\">\n",
			(string)$meta,
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
		$div = new HtmlInterface("div", "class", "classname");
		$this->assertSame(
			"<div class=\"classname\"></div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);
	}

	/** 
    * @test
    * @depends selfClosingTag
    */
	public function selfClosingTagWithSiblings()
	{
		$meta = new HtmlInterface("meta");
		$meta->meta("meta");
		$this->assertSame(
			"<meta>\n<meta>\n",
			(string)$meta,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
    * @test
    * @depends regularTag
    */
	public function regularTagWithChildren()
	{
		$div = new HtmlInterface("div");
		$div-span(
			$div-p()
		);
		$this->assertSame(
			"<div>\n\t<span>\n\t\t<p></p>\n\t</span></div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);
	}

	/** 
    * @test
    * @depends selfClosingTagWithSiblings
    */
	public function selfClosingTagWithAttributesAndSiblings()
	{
		$meta = new HtmlInterface("meta", "content", "content-text");
		$this->assertSame(
			"<meta content=\"content-text\">\n",
			(string)$meta,
			"Failed to return br tag as parent (self-closing)."
		);
	}

	/** 
    * @test
    * @depends regularTagWithChildren
    */
	public function regularTagWithAttributesAndChildren()
	{
		$div = new HtmlInterface("div", "class", "classname");
		$this->assertSame(
			"<div class=\"classname\"></div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);
	}

	/** 
    * @test
    * @depends regularTag
    */
	public function documentFragment()
	{
		$div = new HtmlInterface("div");
		$this->assertSame(
			"<div>\n\t<span>\n\t\t<p></p>\n\t</span>\n</div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);
	}

	/** 
    * @test
    */
	public function minimalDocument()
	{
		//setup
		$html = new HtmlInterface("html");
		$head = new HtmlInterface("head");
		$body = new HtmlInterface("body");
		

		/*$html->body();
		$this->assertSame(
			"<html>\n\t<body></body>\n</html>\n",
			(string)$html,
			"Failed to return html tag as parent."
		);

		$head->meta();
		$this->assertSame(
			"<head>\n\t<meta></head>\n",
			(string)$head,
			"Failed to return head tag as parent."
		);

		$body->p();
		$this->assertSame(
			"<body>\n\t<p></p>\n</body>\n",
			(string)$body,
			"Failed to return body tag as parent."
		);

		$this->assertSame(
			"<div>\n\t<span>\n\t\t<p></p>\n\t</span>\n</div>\n",
			(string)$div,
			"Failed to return div tag as parent."
		);

		*/
	}

	/** 
	* @test
	*/
	public function returnsDoctype()
	{
		
	}

	/** 
	* @test
	*/
	public function toString()
	{

	}
}
