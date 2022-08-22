# Coding Standard validation (PHPCS / PHP_CodeSniffer) 

The coding style is validated using PHPCS and uses the
[Youwe Coding Standard](https://github.com/YouweGit/coding-standard).

During the installation of the testing suite a file called `phpcs.xml` is added to
the root of the repository which refers to the coding standard. To make
adjustments to the coding standard this file can be edited and committed.

[PHPCS](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
[PHP Coding Standards Fixer](https://github.com/squizlabs/PHP_CodeSniffer)

## Common issues

Especially when adapting Testing Suite at a later moment it might be useful
to just ignore some issues and refactor the code later. Doing this inline 
in the code is pretty descriptive, If usage of `phpcs:disable` is used, make
sure to explicitly describe what the intention of this was.
 
```php
<?php
# Some examples of ignoring single lines
// phpcs:ignore Generic.Files.LineLength.TooLong
// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch

# An example of disabling multiple lines
// phpcs:disable Some comment on why this piece of code is not checked with PHPCS
// ...
// phpcs:enable
```


