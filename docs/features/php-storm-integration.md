# PHPStorm Integration

### The basics
When the testing suite is installed in a PHPStorm environment it automatically
configures PHPStorm to use the correct coding style.

To enable PHPCS and PHPMD inspections in PHPStorm the correct binaries need
to be configured. This is a global setting in PHPStorm and can therefore not
be configured by the testing suite.

The recommended way to get the correct binaries is by installing the Youwe
Coding Standard globally.

```
composer global require youwe/coding-standard
```

The package will be installed in the home directory of composer. The location
of this directory can be found using the following command:

```
composer global config home
```

Open PHPStorm and go to __Settings > Languages & Frameworks > PHP > Code Sniffer__.

Choose "Local" for the development environment and fill in the full path to
`<composer_home_directory>/vendor/bin/phpcs`.

Then go to __Settings > Languages & Frameworks > PHP > Mess Detector__.

Choose "Local" for the development environment and fill in the full path to
`<composer_home_directory>/vendor/bin/phpmd`.

After these adjustments the coding style and complexity will be validated
while typing in PHPStorm.

To enable ESLint open PHPStorm and go to
__Settings > Languages & Frameworks > Javascript > Code Quality Tools > ESLint__.

Enable ESLint by checking `Enabled`. Then set the `Node interpreter`
to `Project` and `Configuration file` to `Automatic Search`.

### Additional features
An extra package [youwe/coding-standard-phpstorm](https://github.com/YouweGit/coding-standard-phpstorm)
is available to extend phpstorm and automatically configure some of the inspection profiles,
as well as extend PHPStorm with some standard (Magento 2) file templates to help with (Magento 2) boilerplate.