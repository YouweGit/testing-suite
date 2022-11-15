# Git commit keyword validation

## Purpose
Every commit, blacklisted phrases are scanned within scanned files containing a specified file extension.\
The purpose of these checks is to prevent committing production-breaking or sensative system information.

## Keywords used
**_Note:_** some example configuration is below. Specific packages might override the default configuration.

To prevent accidental commits of specific syntax that may cause issues, the testing suite sniffs\
several keywords in your commits.

### Preventing production logs, debug statements and leaking sensitive system information
```yaml
- "die("
- "dd("
- "var_dump("
- "console.log("
- "alert("
- "print_r("
- "phpinfo("
```

### Preventing accidental committing of merge conflicts
```yaml
- "<<<<<"
- ">>>>>"
- "====="
```

### Preventing statements that have better alternatives
```yaml
- "<?php echo"
```

* The magento2 coding standards extend this with invocation of the ObjectManager.

## Files scanned
The following files are scanned for blacklisted keywords in a default configuration.

**_Note:_** different project types might override the files scanned.
```yaml
- .php
- .js
```
* Magento2 project types extend this with .phtml files.
* Pimcore project types extend this with .twig files.

## Override the configuration
To override the default git blacklist with your own, override the configuration in the `grumphp.yml` file\
in your local project. An example configuration can be found below.

Full details of available configuration options can be found [here](https://github.com/phpro/grumphp/blob/master/doc/tasks/git_blacklist.md).

Note: configuration keys are **overwritten, not merged**.

```yaml
imports:
    - resource: 'vendor/youwe/testing-suite/config/default/grumphp.yml'

parameters:
    git_blacklist.keywords:
        - "die("
        - "dd("
        - "var_dump("
        - "console.log("
        - "alert("
        - "print_r("
        - "phpinfo("
        - "exit;"
        - "<<<<<"
        - ">>>>>"
        - "====="
        - "<?php echo"
        - "My additional keyword"
    git_blacklist.triggered_by: [ 'php', 'js', 'additional_file_extension_here' ]
```
