(ml)Silkworm [![Build Status](https://travis-ci.org/nexocentric/silkworm.png?branch=master)](https://travis-ci.org/nexocentric/silkworm)
============
[日本語はこちらです。](./README[JP].md)

Silkworm is a PHP library to aid in the **creation** of **HTML** and **XML** documents in PHP. Silkworm acts as an abstraction layer allowing you to focus on PHP when you're programming eliminating the need to worry about properly formatting your HTML `\t\t<tags>\n` manually.

Silkworm generates nicely carriaged, returned and indented HTML or XML from your PHP files. The library is dynamic and can be used in a modular fashion allowing you to break up the document creation process into logical chunks throughout your program.

History
-------
Silkworm was built from the ground up and has been throughly tested using PhpUnit.

Changelog
---------
| Version | Name            | Changes         |
|---------|-----------------|-----------------|
| 1.00    | ao              | initial release |

Installation and Configuration
------------------------------
### Installation
Silkworm has no dependencies and can be installed the following ways:
1. GitHub Copy and Include  
  * Copy the library from GitHub  
  * Move Silkworm.php to the directory of your choice  
  * Include Silkworm.php in the file that you'll be using it in  
2. Via Composer  
  * Add the following to your composer requirements
  ```json
  {  
    "require": {  
      "nexocentric/silkworm": "dev-master"  
    }  
  }  
  ```

Instantiate `$html = new Silkworm();` and go.

#### Testing
All tests for Silkworm have been conducted with PhpUnit. The tests are contained in the [tests folder](./tests), so feel free to run them to make sure your version is working.

### Configuration
Silkworm doesn't require any configuration before use. However, there are a number of settings that you can use as demonstrated below.

```php
Silkworm::setSilkwormAlias("HyperTextGenerator"); //change the name of the class

$html = new Silkworm();
$html->setIndentation("   "); //indentation is now set to 3 spaces
$html->setSelfClosingTagStyle("xml"); // < /> vs <>
$html->setBooleanDisplayStyle("maximize"); //disabled="disabled" vs disabled
```

Usage
-----
There are a number of ways to use Silkworm.

### Basic
```php
$table = array(
    array("Version", "Name", "Changes"),
    array("1.00", "ao", "initial release")
);

$html = new Silkworm();
$html->html(
    $html->head(
        $html->title("A title")
    ),
    $html->body(
        $html->p("class", "main-text", "This is Silkworm version 1.00!"),
        $html->newline(),
        $html->comment("information about silkworm"),
        $html->autoTable($table, true)
    )
);
```

### Snippet Saving
You can make and save snippets as follows.

##### Setup
```php
$html = new Silkworm();
$html["error"] = $html->div(
    $html->p(
      "YOU MADE A BOO BOO!"
    )
);

$html["falsePositive"] = $html->div(
    $html->p(
      "Sorry, about that. My bad."
    )
);

$html["truePositive"] = $html->div(
    $html->p(
      "On second thought... that can't be... ;)"
    )
);
```

If you use the `(string)$html` as a string, all of the snippets will automatically be joined in numerical then alphabetical order.

##### Output
```html
<div>
  <p>YOU MADE A BOO BOO!</p>
</div>
<div>
  <p>Sorry, about that. My bad.</p>
</div>
<div>
  <p>On second thought... that can't be... ;)</p>
</div>
```

You can also choose which snippet you would like to use.

```php
(string)$html["falsePositive"];
```

### Advanced
For advanced usage please see the example folder. The examples are set up and ready for display. You should be able to access the files from your browser and see how they display there. Feel free to tinker with the examples to test out the system.

Contact
-------
### General
You can contact me via:
* Twitter: [@nexocentric](https://twitter.com/nexocentric)
* GitHub: [nexocentric](https://github.com/nexocentric)

### Bugs
If you find any bugs while using Silkworm, I'd like to know so that I can fix them as soon as possible.

Please submit the issue via GitHub and I'll contact you for more information.

### Contributing
Your contributions are greatly appreciated!

If you would like to contribute, please:

1. Fork the library on GitHub  
2. Make any changes that you think will better the project  
3. Make tests for the changes that you've made  
4. Make a pull request  
5. I'll message you about making any needed documentation changes (so that you don't make documentation changes before you know if the pull request can be accepted or not)  

I'll go through the request to make sure that everything is okay and usable.*

[*] I would like to apologize in advance for not being able to accept all pull requests.

### Feedback
I would like to hear feedback, bad and good. Anything that promotes discussion is appreciated.

Acknowledgements
----------------
I would like to thank all of the people who supported me through out development for all of their help and advice.

* Amy Kuwahara
* John Goodland
* Tommie Barlow
* Wataru Kitamura

Copyright
---------
The MIT License (MIT)

Copyright (c) 2013 Dodzi Y. Dzakuma (http://www.nexocentric.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.