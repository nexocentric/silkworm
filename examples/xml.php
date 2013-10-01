<?php
////////////////////////////////////////////////////////////////////////////////
// Work     : Silkworm (xml example)
// Copyright: (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)
//                See copyright at footer for more information.
// Version  : 1.00
////////////////////////////////////////////////////////////////////////////////
require_once("../Silkworm.php");

#-----------------------------------------------------------
# example 1 (completely manual - tedious, but has uses)
#-----------------------------------------------------------
$xmlDrinks = new Silkworm("xml"); //this sets silkworm to default to xml styles
$xmlDrinks->xmlVersion("1.0");
$xmlDrinks->drinks(
	$xmlDrinks->cdata("This contains a lot of data about drinks that don't really matter."),
	$xmlDrinks->hot(
		$xmlDrinks->alcohols(
			$xmlDrinks->drink("wine"),
			$xmlDrinks->drink("coffee"),
			$xmlDrinks->drink("rum")
		),
		$xmlDrinks->teas(
			$xmlDrinks->drink("green"),
			$xmlDrinks->drink("oolong"),
			$xmlDrinks->drink("white")
		),
		$xmlDrinks->coffees(
			$xmlDrinks->drink("espresso"),
			$xmlDrinks->drink("milk"),
			$xmlDrinks->drink("black")
		),
		$xmlDrinks->juices(
			$xmlDrinks->drink("apple"),
			$xmlDrinks->drink("orange"),
			$xmlDrinks->drink("tomato")
		),
		$xmlDrinks->sodas(
			$xmlDrinks->drink("ginger ale"),
			$xmlDrinks->drink("apple-hop"),
			$xmlDrinks->drink("caramel")
		)
	),
	$xmlDrinks->cold(
		$xmlDrinks->alcohols(
			$xmlDrinks->drink("vodka"),
			$xmlDrinks->drink("beer"),
			$xmlDrinks->drink("wine")
		),
		$xmlDrinks->teas(
			$xmlDrinks->drink("green"),
			$xmlDrinks->drink("corn"),
			$xmlDrinks->drink("white")
		),
		$xmlDrinks->coffees(
			$xmlDrinks->drink("frappuccino"),
			$xmlDrinks->drink("iced"),
			$xmlDrinks->drink("espresso")
		),
		$xmlDrinks->juices(
			$xmlDrinks->drink("grapefruit"),
			$xmlDrinks->drink("apple"),
			$xmlDrinks->drink("orange")
		),
		$xmlDrinks->sodas(
			$xmlDrinks->drink("cola"),
			$xmlDrinks->drink("root beer"),
			$xmlDrinks->drink("punch")
		)
	)
);

#-----------------------------------------------------------
# example 2 (just to reiterate on the modular example)
#-----------------------------------------------------------
$xmlData = new Silkworm("xml"); //this sets silkworm to default to xml styles
$xmlData->xmlVersion("1.0");
$xmlData->doctype("1.0");
$xmlData["novels"] = $xmlData->writtenMedia( //save to an array like this
	$xmlData->horror(
		$xmlData->work("death"),
		$xmlData->work("pain"),
		$xmlData->work("suffering")
	),
	$xmlData->fantasy(
		$xmlData->work("dragons"),
		$xmlData->work("magic"),
		$xmlData->work("fire-wildthings")
	),
	$xmlData->scienceFiction(
		$xmlData->work("L.A.S.E.R.s"),
		$xmlData->work("hyper warp"),
		$xmlData->work("time parameter")
	)
);
$xmlData["magazines"] = $xmlData->writtenMedia(
	$xmlData->fashion(
		$xmlData->work("shirts"),
		$xmlData->work("suits"),
		$xmlData->work("blouses")
	),
	$xmlData->tech(
		$xmlData->work("gigakeys"),
		$xmlData->work("random access keyboards"),
		$xmlData->work("laundry and lint")
	),
	$xmlData->cooking(
		$xmlData->work("vegetables"),
		$xmlData->work("bagels"),
		$xmlData->work("mexican")
	)
);
$xmlData["newspapers"] = $xmlData->writtenMedia(
	$xmlData->local(
		$xmlData->work("farmer bill"),
		$xmlData->work("effington daily"),
		$xmlData->work("high school")
	),
	$xmlData->national(
		$xmlData->work("tabloid central"),
		$xmlData->work("politics the right way"),
		$xmlData->work("security and you")
	),
	$xmlData->scientific(
		$xmlData->work("relativity"),
		$xmlData->work("obscurity"),
		$xmlData->work("obtuse")
	)
);
$xmlData["comics"] = $xmlData->writtenMedia(
	$xmlData->superhero(
		$xmlData->work("superpunch"),
		$xmlData->work("maggot person"),
		$xmlData->work("vunerable human")
	),
	$xmlData->manga(
		$xmlData->work("squids and octopus"),
		$xmlData->work("ninjas and cowboys"),
		$xmlData->work("firballs and school girls")
	),
	$xmlData->strip(
		$xmlData->work("the cashews"),
		$xmlData->work("kalvin cline"),
		$xmlData->work("diesel powered")
	)
);
$xmlData["blogs"] = $xmlData->writtenMedia(
	$xmlData->news(
		$xmlData->work("the post"),
		$xmlData->work("the site"),
		$xmlData->work("the post site")
	),
	$xmlData->photo(
		$xmlData->work("mine"),
		$xmlData->work("my friend"),
		$xmlData->work("my dad")
	),
	$xmlData->video(
		$xmlData->work("nsfw"),
		$xmlData->work("nsfw x 2"),
		$xmlData->work("nsfw duplicated x 4")
	)
);

//lets write these to a file for use later
file_put_contents("./drinks.xml", (string)$xmlDrinks);
file_put_contents("./novels.xml", (string)$xmlData["novels"]);
file_put_contents("./magazines.xml", (string)$xmlData["magazines"]);
file_put_contents("./newspapers.xml", (string)$xmlData["newspapers"]);
file_put_contents("./comics.xml", (string)$xmlData["comics"]);
file_put_contents("./blogs.xml", (string)$xmlData["blogs"]);

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