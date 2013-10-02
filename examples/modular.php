<?php
////////////////////////////////////////////////////////////////////////////////
// Work     : Silkworm (modular tutorial)
// Copyright: (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)
//                See copyright at footer for more information.
// Version  : 1.00
////////////////////////////////////////////////////////////////////////////////
require_once("../Silkworm.php");

class Webpage
{
	private $webpage = null;
	
	public function __construct()
	{
		$this->webpage = new Silkworm();
		$this->createHead();
		$this->createBody();
	}
	
	private function createHead()
	{
		$this->webpage["a"] = $this->webpage->head(
			$this->webpage->meta("charset", "UTF-8"),
			$this->webpage->title("Silkworm (modular example)"),
			$this->webpage->newline(),
			$this->webpage->meta(
				"name", "description", 
				"content", "This is a demostration of how Silkworm can be encoporated into systems."
			),
			$this->webpage->meta("name", "viewport", "content", "width=device-width"),
			$this->webpage->comment("end standard header block"),
			$this->webpage->newline(),
			$this->webpage->comment("start styles"),
			$this->getStyles(), //I can take care of all my includes here
			$this->webpage->comment("end styles"),
			$this->webpage->newline(),
			$this->webpage->comment("start javascripts"),
			$this->getJavascripts(), //and here as well
			$this->webpage->comment("end javascripts")
		);
	}
	
	private function createBody()
	{
		$this->webpage["b"] = $this->webpage->body(
			$this->getPageContent(),
			$this->webpage->autoTable(
				$this->getTableData(),
				true
			)
		);
	}
	
	private function getJavascripts()
	{
		$javascriptList = new Silkworm();
		$serverJavascriptList = array(
			"./basic.javascript.js",
			"./vendor.javascript.js",
			"./display.javascript.js"
		);

		foreach($serverJavascriptList as $javascript) {
			$javascriptList[] = $javascriptList->script(
				array("type"=>"javascript"),
				array("src"=>$javascript)
			);
		}
		return $javascriptList;
	}
	
	private function getStyles()
	{
		$styleList = new Silkworm();
		$serverStyleList = array(
			"./basic.css",
			"./vendor.css",
			"./custom.css"
		);

		foreach($serverStyleList as $style) {
			$styleList[] = $styleList->style(
				array("type"=>"text/css"),
				array("src"=>$style)
			);
		}
		return $styleList;
	}
	
	private function getTableData()
	{
		return array(
			array("genre", "work 1", "work 2", "work 3"),
			array("horror", "death", "pain", "suffering"),
			array("fantasy", "dragons", "magic", "fire wildthings"),
			array("science fiction", "L.A.S.E.R.s", "hyper warp", "time parameter")
		);
	}
	
	private function getPageContent()
	{
		$content = new Silkworm();
		$content->div(
			$content->p(
				"Just a crappy example. " .
				"This can be improved."
			)
		);
		return $content;
	}
	
	public function display()
	{
		$this->webpage->doctype("html");
		return (string)$this->webpage->html(
			(string)$this->webpage
		);
	}
}

#-----------------------------------------------------------
# remove the comment mark from an example and see how it works :)
#-----------------------------------------------------------
$webpage = new Webpage();
print($webpage->display());

////////////////////////////////////////////////////////////////////////////////
// The MIT License (MIT)
// 
// Copyright (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)
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