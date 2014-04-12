<?php

class SilkwormTest extends PHPUnit_Framework_TestCase
{
	protected function exposePrivateFunction($className, $name)
	{
		$class = new ReflectionClass($className);
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

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
	*/
	public function cdata()
	{
		$cdata = "I think that this might really work!!!!";
		$html = new Silkworm();
		$this->assertSame(
			"<![CDATA[$cdata]]>\n",
			$html->cdata($cdata),
			"Failed to convert interface to string."
		);

	}

	/** 
	* @test
	* @depends toString
	* @depends comment
	*/
	public function setSilkwormClassAlias()
	{
		Silkworm::setSilkwormAlias("HyperTextGenerator");
		$html = new HyperTextGenerator();
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
	* @depends regularTagWithInnerText
	*/
	public function regularTagWithEscapedInnerText()
	{
		$html = new Silkworm();
		$unescapedInnerText = "<script></script>";
		$escapedInnerText = htmlspecialchars($unescapedInnerText);
		$html->div($unescapedInnerText);
		$this->assertSame(
			"<div>${escapedInnerText}</div>\n",
			(string)$html,
			"Failed to escpae inner text."
		);
	}

	/** 
	* @test
	* @depends regularTag
	*/
	public function returnsDoctype()
	{
		$html = new Silkworm();
		$html->doctype("html //definitions");
		$this->assertSame(
			"<!DOCTYPE html //definitions>\n", 
			(string)$html, 
			"Failed to set solidary !DOCTYPE."
		);

		$html = new Silkworm();
		$html->doctype("html //definitions");
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
	public function returnsXMLVersion()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->xmlVersion("1.0");
		$this->assertSame(
			"<?xml version=${qt}1.0${qt}?>\n", 
			(string)$html, 
			"Failed to set solidary !DOCTYPE."
		);

		$html = new Silkworm();
		$html->xmlVersion("1.0");
		$html->html();
		$this->assertSame(
			"<?xml version=${qt}1.0${qt}?>\n<html></html>\n", 
			(string)$html, 
			"Failed to set !DOCTYPE with document HTML."
		);
	}

	/** 
	* @test
	* @depends returnsDoctype
	* @depends returnsXMLVersion
	*/
	public function returnsDoctypeAndXMLVersion()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->xmlVersion("1.0");
		$html->doctype("html //definitions");
		$this->assertSame(
			"<?xml version=${qt}1.0${qt}?>\n" .
			"<!DOCTYPE html //definitions>\n", 
			(string)$html, 
			"Failed to set solidary !DOCTYPE."
		);

		$html = new Silkworm();
		$html->xmlVersion("1.0");
		$html->doctype("html //definitions");
		$html->html();
		$this->assertSame(
			"<?xml version=${qt}1.0${qt}?>\n" .
			"<!DOCTYPE html //definitions>\n" .
			"<html></html>\n", 
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
		$qt = Silkworm::DOUBLE_QUOTE;
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
	* @depends selfClosingTag
	*/
	public function selfClosingTagXMLStyle()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->setSelfClosingTagStyle("x");
		$html->meta("content", "content-text");		
		$this->assertSame(
			"<meta content=${qt}content-text${qt} />\n",
			(string)$html,
			"Space and Slash"
		);

		$html = new Silkworm();
		$html->setSelfClosingTagStyle(" ");
		$html->meta("content", "content-text");		
		$this->assertSame(
			"<meta content=${qt}content-text${qt}>\n",
			(string)$html,
			"space only"
		);

		$html = new Silkworm();
		$html->setSelfClosingTagStyle("A");
		$html->meta("content", "content-text");		
		$this->assertSame(
			"<meta content=${qt}content-text${qt}>\n",
			(string)$html,
			"random letter"
		);
	}

	/** 
	* @test
	* @depends regularTag
	* @depends selfClosingTagWithAttributes
	*/
	public function regularTagWithAttributes()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
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
	* @depends regularTagWithAttributes
	*/
	public function regularTagWithEscapedAttributes()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
		$html = new Silkworm();
		$unescapedAttribute = "<script></script>";
		$escapedAttribute = htmlspecialchars($unescapedAttribute);
		$html->div("class", $unescapedAttribute);
		$this->assertSame(
			"<div class=${qt}${escapedAttribute}${qt}></div>\n",
			(string)$html,
			"Failed to return div with escaped attributes."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithAttributes
	*/
	public function selfClosingTagWithAttributesFromArray()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
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
		$qt = Silkworm::DOUBLE_QUOTE;

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
		$qt = Silkworm::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->setBooleanDisplayStyle("mi"); //does the short version work
		$html->input("type", "checkbox", "checked", "disabled");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} checked disabled>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle("MAXIMIZED"); //does all caps work
		$html->input("type", "checkbox", "checked", "disabled");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} checked=${qt}checked${qt} disabled=${qt}disabled${qt}>\n",
			(string)$html,
			"Failed to return br tag as parent (self-closing)."
		);
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle("boolean"); //does all lowercase work
		$html->input("type", "checkbox", "checked", "disabled");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} checked=${qt}true${qt} disabled=${qt}true${qt}>\n",
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
		//declarations
		$qt = Silkworm::DOUBLE_QUOTE;
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
		$html->button("hidden", "disabled", "click me");
		$this->assertSame(
			"<button hidden disabled>click me</button>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle("MA"); //does the caps short version work
		$html->button("hidden", "disabled", "click me");
		$this->assertSame(
			"<button hidden=${qt}hidden${qt} disabled=${qt}disabled${qt}>click me</button>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle("BoOl"); //does half the word random caps work
		$html->button("hidden", "disabled", "click me");
		$this->assertSame(
			"<button hidden=${qt}true${qt} disabled=${qt}true${qt}>click me</button>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithBooleanAttributes
	*/
	public function selfClosingTagWithUserDefinedBooleanAttributes()
	{
		//declarations
		$qt = Silkworm::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->setBooleanDisplayStyle("mi"); //does the short version work
		$html->defineBooleanAttributes("sugar-coated", "optimally-coated"); //regular parameters
		$html->input("type", "checkbox", "sugar-coated", "optimally-coated");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} sugar-coated optimally-coated>\n",
			(string)$html,
			"Failed to define boolean attributes from parameters."
		);
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle("MAXIMIZED"); //does all caps work
		$html->defineBooleanAttributes(array("sugar-coated", "optimally-coated")); //array of parameters
		$html->input("type", "checkbox", "sugar-coated", "optimally-coated");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} sugar-coated=${qt}sugar-coated${qt} optimally-coated=${qt}optimally-coated${qt}>\n",
			(string)$html,
			"Failed to define boolean attributes from array."
		);
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle("boolean"); //does all lowercase work
		$html->defineBooleanAttributes("sugar-coated, optimally-coated"); //comma delimited
		$html->input("type", "checkbox", "sugar-coated", "optimally-coated");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} sugar-coated=${qt}true${qt} optimally-coated=${qt}true${qt}>\n",
			(string)$html,
			"Failed to define boolean attributes from comma delimited list."
		);

		$html = new Silkworm();
		$html->setBooleanDisplayStyle("boolean"); //does all lowercase work
		$html->defineBooleanAttributes( //mixed
			"sugar-coated, optimally-coated", 
			array("cream-filled", "fully-loaded"),
			"filled-with-goodness"
		);
		$html->input("type", "checkbox", "sugar-coated", "optimally-coated", "cream-filled", "fully-loaded", "filled-with-goodness");
		$this->assertSame(
			"<input type=${qt}checkbox${qt} sugar-coated=${qt}true${qt} optimally-coated=${qt}true${qt} cream-filled=${qt}true${qt} fully-loaded=${qt}true${qt} filled-with-goodness=${qt}true${qt}>\n",
			(string)$html,
			"Failed to define boolean attributes from mixed types."
		);
	}

	/** 
	* @test 
	* @depends regularTagWithBooleanAttributes
	* @depends selfClosingTagWithUserDefinedBooleanAttributes
	*/
	public function regularTagWithUserDefinedBooleanAttributes()
	{
		//declarations
		$qt = Silkworm::DOUBLE_QUOTE;
		
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
		$html->defineBooleanAttributes("filled-with-goodness");
		$html->button("disabled", "filled-with-goodness", "click me");
		$this->assertSame(
			"<button disabled filled-with-goodness>click me</button>\n",
			(string)$html,
			"Failed to define boolean attributes for use in regular tag."
		);
	}

	/** 
	* @test
	* @depends selfClosingTagWithAttributesFromArray
	* @depends selfClosingTagWithBooleanAttributes
	*/
	public function selfClosingTagWithAttributesFromArrayAndStrings()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;

		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		//setup
		$html = new Silkworm();
		$html->doctype("html");
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
	* @depends completeDocument
	*/
	public function completeXMLDocument()
	{
		$qt = Silkworm::DOUBLE_QUOTE;
		//setup
		$html = new Silkworm("xml");
		$html->xmlVersion("1.0");
		$html->html(
			$html->head(
				$html->meta("name", "description")
			),
			$html->body(
				$html->p("document")
			)
		);
		$this->assertSame(
			"<?xml version=${qt}1.0${qt}?>\n" .
			"<html>\n" .
			"\t<head>\n" .
			"\t\t<meta name=${qt}description${qt} />\n" .
			"\t</head>\n" .
			"\t<body>\n" . 
			"\t\t<p>document</p>\n" .
			"\t</body>\n" .
			"</html>\n", 
			(string)$html
		);

		$html = new Silkworm();
		$html->setSelfClosingTagStyle("x");
		$html->xmlVersion("1.0");
		$html->html(
			$html->head(
				$html->meta("name", "description")
			),
			$html->body(
				$html->p("document")
			)
		);
		$this->assertSame(
			"<?xml version=${qt}1.0${qt}?>\n" .
			"<html>\n" .
			"\t<head>\n" .
			"\t\t<meta name=${qt}description${qt} />\n" .
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
	* @depends returnsDoctype
	* @depends documentFragment
	*/
	public function clearDoctype()
	{
		$body = new Silkworm();
		$body->doctype("html //definitions");
		$body->body();

		$html = new Silkworm();
		$html->doctype("html //definitions");
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
		$qt = Silkworm::DOUBLE_QUOTE;
		//setup
		$html = new Silkworm();
		$html->doctype("html");
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$table = array(
			array("a", "b", "c"),
			array("d", "e", "f"),
			array("g", "h", "i")
		);
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
		$table = array(
			array("class=>left"=>"a", "class=middle"=>"b", "class, right"=>"c")
		);
		$html = new Silkworm();
		$html->setBooleanDisplayStyle(); //no string defaults to minimized
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
		$qt = Silkworm::DOUBLE_QUOTE;
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
	
	/** 
	* @test
	*/
	public function zeroPadArrayIndex()
	{
		#---------------------------------------
		# initalizations
		#---------------------------------------
		$dummySilkwormClass = new Silkworm();
		$testIndexInteger = 300;

		#---------------------------------------
		# expose private method for testing
		#---------------------------------------
		$functionCalculateZeroPaddingLength = $this->exposePrivateFunction(
			"Silkworm", 
			"calculateZeroPaddingLength"
		);
		$functionZeroPadArrayIndex = $this->exposePrivateFunction(
			"Silkworm", 
			"zeroPadArrayIndex"
		);

		#---------------------------------------
		# the length that should be calculated
		# by the Silkworm class
		#---------------------------------------
		$zeroPaddingLength = $functionCalculateZeroPaddingLength->invokeArgs(
			$dummySilkwormClass, 
			array()
		);

		#---------------------------------------
		# the padded index
		#---------------------------------------
		$paddedIndex = $functionZeroPadArrayIndex->invokeArgs(
			$dummySilkwormClass, 
			array($testIndexInteger)
		);
		
		#---------------------------------------
		# check to make sure the index is
		# properly padded
		#---------------------------------------
		$this->assertSame(
			sprintf("%0${zeroPaddingLength}s", $testIndexInteger),
			$paddedIndex,
			"Failed to zero pad the index in question."
		);
	}

	/** 
	* @test
	* @depends zeroPadArrayIndex
	* @depends documentFragment
	*/
	public function saveAccessFragmentNumericKey()
	{
		$html = new Silkworm();
		$html[] = $html->div(
			$html->comment("this works"),
			$html->h1(),
			$html->newline(),
			$html->span(
				$html->p(
				)
			)
		);
		$this->assertSame(
			"<div>\n" .
			"\t<!-- this works -->\n" .
			"\t<h1></h1>\n" .
			"\t\n" .
			"\t<span>\n" .
			"\t\t<p></p>\n" .
			"\t</span>\n" .
			"</div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
		$this->assertSame(
			"<div>\n" .
			"\t<!-- this works -->\n" .
			"\t<h1></h1>\n" .
			"\t\n" .
			"\t<span>\n" .
			"\t\t<p></p>\n" .
			"\t</span>\n" .
			"</div>\n",
			$html[0],
			"Failed to return div tag as parent."
		);
	}
	
	/** 
	* @test
	* @depends documentFragment
	*/
	public function saveAccessFragmentNamedKey()
	{
		$html = new Silkworm();
		$html["a"] = $html->div(
			$html->comment("this works"),
			$html->h1(),
			$html->newline(),
			$html->span(
				$html->p(
				)
			)
		);
		$this->assertSame(
			"<div>\n" .
			"\t<!-- this works -->\n" .
			"\t<h1></h1>\n" .
			"\t\n" .
			"\t<span>\n" .
			"\t\t<p></p>\n" .
			"\t</span>\n" .
			"</div>\n",
			(string)$html,
			"Failed to return div tag as parent."
		);
		$this->assertSame(
			"<div>\n" .
			"\t<!-- this works -->\n" .
			"\t<h1></h1>\n" .
			"\t\n" .
			"\t<span>\n" .
			"\t\t<p></p>\n" .
			"\t</span>\n" .
			"</div>\n",
			$html["a"],
			"Failed to return div tag as parent."
		);
	}
	
	/** 
	* @test
	* @depends saveAccessFragmentNumericKey
	* @depends saveAccessFragmentNamedKey
	*/
	public function saveFragmentRandomRetrieveNumericAlphaOrder()
	{
		$html = new Silkworm();
		$html["c"] = $html->newline(); //this is c
		$html[3] = $html->h1();
		$html[] = $html->comment("this works");
		$html["f"] = $html->span( //this is d
			$html->p()
		);
		$this->assertSame(
			"<div>\n" .
			"\t<!-- this works -->\n" .
			"\t<h1></h1>\n" .
			"\t\n" .
			"\t<span>\n" .
			"\t\t<p></p>\n" .
			"\t</span>\n" .
			"</div>\n",
			(string)$html->div(
				(string)$html
			),
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends saveAccessFragmentNumericKey
	* @depends saveAccessFragmentNamedKey
	*/
	public function retrieveNonexistantFragment()
	{
		$html = new Silkworm();
		$html["a"] = $html->comment("this works");
		unset($html["a"]);
		$this->assertSame(
			"<div></div>\n",
			(string)$html->div(
				(string)$html
			),
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function displaySavedFragmentAfterUnset()
	{
		$html = new Silkworm();
		$html[2] = $html->newline(); //this is c
		$html[0] = $html->h1();
		$html["a"] = $html->comment("this works");
		$html["b"] = $html->span( //this is d
			$html->p()
		);
		unset($html["a"]);
		$this->assertSame(
			"<div>\n" .
			"\t<h1></h1>\n" .
			"\t\n" .
			"\t<span>\n" .
			"\t\t<p></p>\n" .
			"\t</span>\n" .
			"</div>\n",
			(string)$html->div(
				(string)$html
			),
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function autoJoinFragmentsWithHeader()
	{
		$html = new Silkworm();
		$html->doctype("html");
		$html[0] = $html->h1("hello");
		$html["zebra"] = $html->span( //this is d
			$html->p("stripes")
		);
		$html["comment"] = $html->comment("this works");
		
		$this->assertSame(
			"<!DOCTYPE html>\n" .
			"<h1>hello</h1>\n" .
			"<!-- this works -->\n" .
			"<span>\n" .
			"\t<p>stripes</p>\n" .
			"</span>\n",
			$html->stringWithDocumentHeader(
				(string)$html
			),
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function manualJoinFragments()
	{
		$html = new Silkworm();
		$html[0] = $html->h1("hello");
		$html["zebra"] = $html->span( //this is d
			$html->p("stripes")
		);
		$html["comment"] = $html->comment("this works");
		
		$this->assertSame(
			"<h1>hello</h1>\n" .
			"<!-- this works -->\n" .
			"<span>\n" .
			"\t<p>stripes</p>\n" .
			"</span>\n",
			(string)$html[0] .
			(string)$html["comment"] .
			(string)$html["zebra"],
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function manualJoinFragmentWithHeader()
	{
		$html = new Silkworm();
		$html->doctype("html");
		$html[0] = $html->h1("hello");
		$html["zebra"] = $html->span( //this is d
			$html->p("stripes")
		);
		$html["comment"] = $html->comment("this works");
		
		$this->assertSame(
			"<!DOCTYPE html>\n" .
			"<h1>hello</h1>\n" .
			"<!-- this works -->\n" .
			"<span>\n" .
			"\t<p>stripes</p>\n" .
			"</span>\n",
			$html->stringWithDocumentHeader(
				(string)$html[0] .
				(string)$html["comment"] .
				(string)$html["zebra"]
			),
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function fragmentOfFragments()
	{
		$html = new Silkworm();
		$html->doctype("html");
		$html[0] = $html->h1("hello");
		$html["zebra"] = $html->span( //this is d
			$html->p("stripes")
		);
		$html["comment"] = $html->comment("this works");
		$html["content"] = $html->div(
			(string)$html[0],
			(string)$html["comment"],
			(string)$html["zebra"]
		);
		
		$this->assertSame(
			"<div>\n" .
			"\t<h1>hello</h1>\n" .
			"\t<!-- this works -->\n" .
			"\t<span>\n" .
			"\t\t<p>stripes</p>\n" .
			"\t</span>\n" .
			"</div>\n",
			(string)$html["content"],
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function fragmentOfFragmentsWithHeader()
	{
		$html = new Silkworm();
		$html->doctype("html");
		$html[0] = $html->h1("hello");
		$html["zebra"] = $html->span( //this is d
			$html->p("stripes")
		);
		$html["comment"] = $html->comment("this works");
		$html["content"] = $html->div(
			(string)$html[0],
			(string)$html["comment"],
			(string)$html["zebra"]
		);
		
		$this->assertSame(
			"<!DOCTYPE html>\n" .
			"<div>\n" .
			"\t<h1>hello</h1>\n" .
			"\t<!-- this works -->\n" .
			"\t<span>\n" .
			"\t\t<p>stripes</p>\n" .
			"\t</span>\n" .
			"</div>\n",
			$html->stringWithDocumentHeader(
				(string)$html["content"]
			),
			"Failed to return div tag as parent."
		);
	}
	
	/**
	* @test
	* @depends retrieveNonexistantFragment
	*/
	public function fragmentOfFragmentsInHtmlTag()
	{
		$html = new Silkworm();
		$html->doctype("html");
		$html[0] = $html->h1("hello");
		$html["zebra"] = $html->span( //this is d
			$html->p("stripes")
		);
		$html["comment"] = $html->comment("this works");
		$html["content"] = $html->div(
			(string)$html[0],
			(string)$html["comment"],
			(string)$html["zebra"]
		);
		
		$this->assertSame(
			"<html>\n" .
			"\t<div>\n" .
			"\t\t<h1>hello</h1>\n" .
			"\t\t<!-- this works -->\n" .
			"\t\t<span>\n" .
			"\t\t\t<p>stripes</p>\n" .
			"\t\t</span>\n" .
			"\t</div>\n" .
			"</html>\n",
			(string)$html->html(
				$html["content"]
			),
			"Failed to return div tag as parent."
		);
	}
}
