        configuration instructions
        installation instructions
        operating instructions
        a file manifest
        copyright and licensing information
        contact information for the distributor or programmer
        known bugs
        troubleshooting
        credits and acknowledgements
        a changelog

    name of the projects and all sub-modules and libraries (sometimes they are named different and very confusing to new users)
    descriptions of all the project, and all sub-modules and libraries
    5-line code snippet on how its used (if it's a library)
    copyright and licensing information (or "Read LICENSE")
    instruction to grab the documentation
    instructions to install, configure, and to run the programs
    instruction to grab the latest code and detailed instructions to build it (or quick overview and "Read INSTALL")
    list of authors or "Read AUTHORS"
    instructions to submit bugs, feature requests, submit patches, join mailing list, get announcements, or join the user or dev community in other forms
    other contact info (email address, website, company name, address, etc)
    a brief history if it's a replacement or a fork of something else
    legal notices (crypto stuff)


HyperTextLoom
=============

Hyper Text Loom is a PHP library to aid in the creation of HTML documents in PHP. Hyper Text Loom is an abstraction layer from HTML which allows you to focus on PHP when you're programming in PHP and worry about HTML presentation later.

Hyper Text Loom generates nicely carriaged returned and indented HTML from your PHP files. The library is dynamic and can be used in a modular fashion allowing you to break up the document creation process into logical chunks throughout your program.


Installation and Configuration
------------------------------

Hyper Text Loom has no dependencies and can be used after inclusion of the HyperTextLoom.php file.

Hyper Text Loom doesn't require any configuration before use. Currently, only the character that is used for indentation can be set. You can choose from a combination of tabs and spaces.

$html = new HyperTextLoom();
$html->setIndentationCharacter("   "); //indentation is now set to 3 spaces


Usage
-----
