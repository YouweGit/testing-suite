# Coding Standard validation (PHPCS / PHP_CodeSniffer) 

The coding style is validated using PHPCS and uses the
[Youwe Coding Standard](https://github.com/YouweGit/coding-standard).

During the installation of the testing suite a file called `phpcs.xml` is added to
the root of the repository which refers to the coding standard. To make
adjustments to the coding standard this file can be edited and committed.

[PHPCS](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
[PHP Coding Standards Fixer](https://github.com/squizlabs/PHP_CodeSniffer)

## Common issues

// phpcs:ignore Generic.Files.LineLength.TooLong
// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch

// phpcs:disable
// phpcs:ensable

// phpcs:disable Magento2.Templates.ThisInTemplate.FoundThis
// phpcs:disable Magento2.Files.LineLength.MaxExceeded
// phpcs:disable Magento2.Security.LanguageConstruct.DirectOutput
