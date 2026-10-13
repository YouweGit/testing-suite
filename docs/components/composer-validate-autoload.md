# Composer Autoload Validate

> **Note:** This task requires a Composer >=2.6 runtime and is therefore disabled by default.

The [Composer Validate Autoload](https://github.com/phpro/grumphp/blob/v2.x/doc/tasks/composer_validate_autoload.md) is
a command line tool that validates whether the autoload configuration in your Composer is correctly applied in the
source code. For example, it will raise an issue if the filename doesn't match the class name.

The following options are available:

```yaml
parameters:
    # Disabled by default, you can enable it if you use a Composer >=2.6 runtime.
    composer_validate_autoload.enabled: true|false
    
    # Checks whether the same class is ever defined in multiple files. It is set to false by default, as enabling it can
    # result in false positives -- especially in the common case where polyfill packages are present in the vendor
    # directory.
    composer_validate_autoload.strict_ambiguous: true|false
```
