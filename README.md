Hyper Text Silkworm
===================

Hyper Text Silkworm is a PHP library to aid in the creation of HTML documents in PHP. Hyper Text Silkworm is an abstraction layer from HTML which allows you to focus on PHP when you're programming in PHP and worry about HTML presentation later.

Hyper Text Silkworm generates nicely carriaged returned and indented HTML from your PHP files. The library is dynamic and can be used in a modular fashion allowing you to break up the document creation process into logical chunks throughout your program.

History
-------
Hyper Text Silkworm was built from the ground up, but was inspired by naomik's htmlgen.

Changelog
---------
| Version | Name            | Changes         |
|---------|-----------------|-----------------|
| 1.00    | ??              | initial release |


Installation and Configuration
------------------------------
### Installation
Hyper Text Silkworm has no dependencies and can be installed one of two ways.
1. Via Composer
..*
2. Copy and Include
..*

### Configuration
Hyper Text Silkworm doesn't require any configuration before use. Currently, only the character that is used for indentation can be set. You can choose from a combination of tabs and/or spaces.

```php
$html = new HyperTextSilkworm();
$html->setIndentationCharacter("   "); //indentation is now set to 3 spaces
```

Usage
-----
### Basic

### Advanced
For advanced usage please see the example folder. The examples are set up and ready for display. You should be able to access the files from your browser and see how they display there. Feel free to tinker with the examples to test out the system.

Copyright
---------

Contact
-------
### General

### Bugs

### Contributing

Acknowledgements
----------------
I would like to thank all of the people who supported me through out development for all of their help and advice.

* Tommie Barlow
* Wataru Kitamura