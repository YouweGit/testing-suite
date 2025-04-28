## Testing suite v2 to v3 migration guide
TODO: Validate / update / rewrite this based on experience and later changes when adding the testing suite
based on experiences with initial v3.0.0-rc1 tests.

### 1. Double check your project type in the project composer.json.
We use the project type to automatically update some ruleset mappings for PHPCS and PHPMD.

For a list of supported project types, see the [readme](./README.md).
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

### 3. Sanity checks
Check the following

1. The PHPCS file exists in your project root and points to the correct ruleset
configuration in youwe/testing-suite
2. The PHPMD file exists in your project root and points to the correct ruleset
configuration in youwe/testing-suite
3. Run `ddev exec grumphp run` or `vendor/bin/grumphp run`
4. Your git commit hook still functions as expected

### 4. Refactor and/or update/regenerate exclusion rules
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
