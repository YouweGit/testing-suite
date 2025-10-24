# Coding Standard validation (PHP CS Fixer) 

The coding style can be validated using [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer).

## Configuration

To start using PHP CS Fixer, make sure you enable it via `grumphp.yml`. By default, it is disabled in favour of PHPCS
(unless this is overridden in the project type specific Grump configuration). See the example configuration below.

Any ruleset configuration and file excludes should be configured in the `.php-cs-fixer.php` file in your project. See 
the [PHP CS Fixer documentation](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/config.rst) for all 
available options.

#### `gruhmphp.yml`
```yaml
imports:
    - resource: 'vendor/youwe/testing-suite/config/default/grumphp.yml'

parameters:

    # Disable PHPCS (which is enabled by default) in favour of PHP CS Fixer
    phpcs.enabled: false
    phpcsfixer.enabled: true

    # Additional options (with its defaults):
    phpcsfixer.allow_risky: false
    phpcsfixer.verbose: true
    phpcsfixer.diff: true
    phpcsfixer.triggered_by: ['php']
```

#### `.php-cs-fixer.php`
```php
<?php

declare(strict_types=1);

/**
 * PHP CS Fixer configuration for this project. For documentation @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/config.rst
 */

$finder = PhpCsFixer\Finder::create()                       // @phpstan-ignore class.notFound (class lives in phar file and can't be seen)
    ->in(__DIR__)
    ->exclude([
        // Symfony files
        'bin/console',
        'public/index.php',
        'var',
    ]);

$config = new PhpCsFixer\Config();                          // @phpstan-ignore class.notFound (class lives in phar file and can't be seen)
return $config                                              // @phpstan-ignore class.notFound (class lives in phar file and can't be seen)
    ->setRules([
        // Alias for the latest revision of PER-CS rules, replace with '@PER-CS2.0' if you want explicitly use PER 2.0
        '@PER-CS' => true,
    ])
    ->setFinder($finder);
```
