Hyper Text Silkworm
===================
[English here.](./README)
[日本語はこちらです。](./README.jp)

Hyper Text Silkworm is a PHP library to aid in the creation of HTML documents in PHP. Hyper Text Silkworm is an abstraction layer from HTML which allows you to focus on PHP when you're programming in PHP and worry about HTML presentation later.

Hyper Text Silkworm generates nicely carriaged returned and indented HTML from your PHP files. The library is dynamic and can be used in a modular fashion allowing you to break up the document creation process into logical chunks throughout your program.

History
-------
Hyper Text Silkworm was built from the ground up and has been throughly tested using PhpUnit.

Changelog
---------
| Version | Name            | Changes         |
|---------|-----------------|-----------------|
| 1.00    | ao              | initial release |

Installation and Configuration
------------------------------
### Installation
Hyper Text Silkworm has no dependencies and can be installed the following ways:
1. GitHub Copy and Include
  * Copy the library from GitHub
  * Move HyperTextSilkworm.php to the directory of your choice
  * Include HyperTextSilkworm.php in the file that you'll be using it in
2. Via Composer
  * Add the following to your composer requirements
  ```json
  {
    "require": {
      "nexocentric/silkworm": "1.*"
    }
  }
  ```
Instantiate `$html = new HyperTextSilkworm();` and go.

#### Testing
All test for Hyper Text Silkworm have been conducted with PhpUnit. The tests are contained in the [tests folder](./tests), so feel free to run them to make sure your version is working.

### Configuration
Hyper Text Silkworm doesn't require any configuration before use. Currently, only the character that is used for indentation can be changed. You can choose from a combination of tabs and/or spaces.

```php
$html = new HyperTextSilkworm();
$html->setIndentation("   "); //indentation is now set to 3 spaces
```

Usage
-----
There are a number of ways to use Hyper Text Silkworm.

### Basic
```php
$table = array(
    array("Version", "Name", "Changes"),
    array("1.00", "ao", "initial release")
);

$html = new HyperTextSilkworm("html"); //instantiate with a <!DOCTYPE html>
$html->html(
    $html->head(
        $html->title("A title")
    ),
    $html->body(
        $html->p("class", "main-text", "This is Hyper Text Silkworm version 1.00!"),
        $html->newline(),
        $html->comment("information about silkworm"),
        $html->autoTable($table, true)
    )
);
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
If you find any bugs while using Hyper Text Silkworm, I'd like to know so that I can fix them as soon as possible.

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

Acknowledgements
----------------
I would like to thank all of the people who supported me through out development for all of their help and advice.

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