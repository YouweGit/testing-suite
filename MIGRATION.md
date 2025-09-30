## Testing suite v2 to v3 migration guide
TODO: Validate / update / rewrite this based on experience and later changes when adding the testing suite
based on experiences with initial v3.0.0-rc1 tests.

### 1. Double check your project type in the project composer.json.
We use the project type to automatically update some ruleset mappings for PHPCS and PHPMD.

For a list of supported project types, see the [readme](./README.md).

If your project type was configured in `composer.json` within the `config` section, then move that to the `extra` 
section:

**Old:**
```json
{
    "config": {
        "youwe-testing-suite": { "type": "magento2" }
    }
}
```

**New:**
```json
{
    "extra": {
        "youwe-testing-suite": { "type": "magento2" }
    }
}
```

Otherwise, the project type is still taken from the `type` within `composer.json`.

```json
{
    "type": "magento2-module"
}
```

### 2. Update to version 3
```bash
composer require --dev youwe/testing-suite:^3.0 --no-update
composer remove --dev youwe/coding-standard youwe/coding-standard-magento2 --no-update
composer update youwe/testing-suite -W
composer install
```

Note: starting v3, phpunit may be installed in your project automatically.\
We use the @stable version constraint for this. If you want to install a specific
phpunit version in your project you are free to do so. Upstream phpunit versions
are honored during installation.

### 3. Change PHPStan configuration

PHPStan is now configured to scan the full project, also during commit hooks. This will require to
configure the `paths` setting in your projects `phpstan.neon.`.

Example `phpstan.neon` file containing the `paths` parameter:
```neon
includes:
  # To create a baseline run the following command and then uncomment the include below (make sure to run the baseline
  # command with the same level as configured in Grumphp)
  #      vendor/bin/phpstan analyse --configuration=./phpstan.neon --generate-baseline --level=4
  # - phpstan-baseline.neon

parameters:
  paths:
    - src
    
    # Uncomment when your project has unit tests:
    # - tests

    # Add any other project folder containing source files, e.g.
    # - bundles

  excludePaths:
    # - tests/fixtures/*
```

As alternative, you can revert your project to the old behaviour by setting the `phpstan.use_grumphp_paths: true` 
parameter in your `grumphp.yml`. Please read [Why you should always analyse the whole project](https://phpstan.org/blog/why-you-should-always-analyse-whole-project)
before reverting to the old behaviour.

### 4. Sanity checks
Check the following

1. The PHPCS file exists in your project root and points to the correct ruleset
configuration in youwe/testing-suite
2. The PHPMD file exists in your project root and points to the correct ruleset
configuration in youwe/testing-suite
3. Run `ddev exec grumphp run` or `vendor/bin/grumphp run`
4. Your git commit hook still functions as expected

### 5. Refactor and/or update/regenerate exclusion rules
Some rulesets will have changed. In a general sense, the rulesets are less
strict compared to what they were before.

If your grumphp tasks are failing due to changes in rulesets, you have 3 options:

1. Refactor. This is always the preferred route if the amount of issues raised
is limited.
2. Add exclusion filters. Use existing tooling or update your project configuration
files to exclude existing project files. This will help in the short-term. Note
that it is always recommended to still refactor in the future so the file
exclusions lists will reduce in size over time.
3. Update rulesets in your project. Projects are free to update rulesets and
validation if they want. Of course it is always recommended to add
additional rules instead of removing existing rules.
