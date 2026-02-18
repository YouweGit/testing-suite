# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.3]
### Changed
- Magento phpmd.xml loosened some rules to be more inline with what is 'normal' in Magento.
  - Added exclude patterns for *.phtml
  - Added exclude for UnusedFormalParameter to allow unused parameters in a function
  - Added exception for short variable name for $id
 - PHPCS Excluded Magento2.Annotation.MethodAnnotationStructure.MethodAnnotation
### Fixed
- Updated property for LongVariable did not work.

## [3.0.2]
### Changed
- Constraint for `bitexpert/phpstan-magento` from '~0.30' to '>=0.30' to allow for installing newer PHPStan versions when needed.

## [3.0.1]
### Fixed
- Updated default `phpunit_dist.xml` to standards of PHPUnit version 10 and higher.

## [3.0.0]
### Added
- Added `phpunit/phpunit` to suggested dependencies in `composer.json`.
- Added `youwe/coding-standard-phpstorm` to suggested dependencies in `composer.json`.
- Added support to honor upstream version constraints.
- Github action for php 8.3 and php 8.4 to run unit tests against PHPUnit 12.
- Testing suite now attempts to install phpunit upstream if it isn't available yet.
  - Existing upstream versions are honored if already installed.
  - Upstream projects not having phpunit installed will install phpunit with an @stable version.
- Added support for Drupal configuration and templates.
- Migration docs for migration from v2 to v3 of the testing suite.
- Option to use PHP CS Fixer instead of PHPCS.
- Pimcore coding standards with [PER coding standards](https://www.php-fig.org/per/coding-style/).
- Added support for an Allow List within the Security Checker.
- Pimcore PHPStan default config.
- Pimcore PHP Mess Detector default config.
- Added constraint for `PHPMD`, since Magento 2.4.8 requires version `3.x-dev`. 
- Commit Message validator to validate the commit message adheres to the 
  [Conventional Commit Message structure](https://cheatography.com/albelop/cheat-sheets/conventional-commits/)
  and that the commit message contains a Jira ticket number it relates to.
- Prevent installation of `phpro/grumphp-shim:2.18.0` to prevent error "symfony/cache" conflicts with extension `redis`
  , see [github link](https://github.com/phpro/grumphp-shim/issues/30) 

### Changed
- [BREAKING] The composer.json configurations `config.youwe-testing-suite.type` and `config.mediact-testing-suite.type`
  are now moved from the `config` section to the `extra` section. The old location was invalid as the `config` section 
  belongs to configuration from Composer itself. Moving it to the `extra` section which is intended for this purpose 
  (composer docs: "arbitrary extra data for consumption by scripts"). Only the single `extra.youwe-testing-suite.type` 
  is supported now. Update your project `composer.json` accordingly.
- [BREAKING] PHPStan is now configured to scan the full project, also during commit hooks. This will require to 
  configure the `paths` setting in your projects `phpstan.neon.`. See the [migration notes](MIGRATION.md) for more
  precise instructions. This behaviour can be modified with the `phpstan.use_grumphp_paths` parameter in `grumphp.yml`.
  Please read [Why you should always analyse the whole project](https://phpstan.org/blog/why-you-should-always-analyse-whole-project)
  before reverting to the old behaviour.
- Unit tests as part of the testing suite are rewritten for PHPUnit 12.
- Updated GitHub Action workflows to support PHP 8.1, 8.2, 8.3, and 8.4
- `composer.json`: Dropped support for PHP < 8.1.
- Moved phpunit from require to require-dev.
- Changed PHPMD suppressions in docblocks to quote the rule name, due to changes in later versions of PHPStan that create false positives on these docblocks if not quoted.
- Moved existing project-type specific rulesets from inner dependencies to testing-suite package.
- Simplified PHPMD rulesets with rationale behind rule changes.
- Updated remote schema location URL for phpmd rulesets to prevent redirecting which may cause flaky builds.
- Bumped phpro/grumphp-shim dependency from v1 to v2
- Bumped youwe/composer-dependency-installer from v1 to v2
- Testing Suite files are only installed in the project when the package itself is installed and/or updated, for example 
  when running `composer require youwe/testing-suite`, `composer update [youwe/testing-suite]` or `composer install`
  when the package was not installed (yet/anymore).
- Default Pimcore coding standards disables PHPCS in favour of PHP CS Fixer.
- JSON Lint will ignore folders `.ddev/` and `tests/fixtures/`.
- Added Symfony function `dump()` to the git blacklist for all project types
- Added new excludes to magento 2 phpcs rules.

### Removed
- Removed support for EOL PHP versions. Projects running PHP < 8.1 can stick to version 2 of the testing-suite.
- Removed support for Composer 1. Projects still relying on Composer 1 can stick to version 2 of the testing-suite.
- Removed `youwe/coding-standard-phpstorm` as dependency (it is still listed in suggest).
- Removed `phpunit/phpunit` as direct dependency (it is still in require-dev and installed upstream through the `youwe/dependency-installer`).
- Github actions for php < 8.1.
- Dependency on seperated coding style packages to simplify development and remove overhead.
- Dropped support for Laravel and Magento 1.
- Dropped inner dependencies on coding-standard, coding-standard-magento2, and coding-standard-phpstorm packages.

### Fixed
- The Composer Config Installer changed empty objects (e.g. a `"autoload-dev": {"psr-4": {}}`) into an empty array
  (for previous example: `"autoload-dev": {"psr-4": []}`) causing an invalid `composer.json` file (followed by other
  composer operations not being able to be performed). This is now fixed.

## 2.19.1
### Changed
- `^0.30` restricts updates to only versions within the `0.30.x` range, preventing upgrades to 0.32.0 for
  `bitexpert/phpstan-magento` to allow upgrading within a major version notation `~0.30` is used.

## 2.19
### Added
- Option to add/edit whitelist or ignore patterns to yamllint.

## 2.18
### Added
- Grumphp will run the git hook in DDev (if available)

## 2.17.1
### Added
- Added `ignore_patterns` support to allow project configurations to make use of this field through
configuration overrides

## 2.17.0
### Added
- BitExpert PHP stan module for Magento 2 projects.

## 2.16.2
### Changed
- `alert(` was removed from the git blacklist as it conflicts with PSR3, see
  [#18](https://github.com/YouweGit/testing-suite/issues/18)

## 2.16.1
### Changed
- Git blacklist now matches on words instead of characters by default.
- The git blacklist configuration for rejecting accidentally commited merge conflicts now properly reflects the
full set of characters used by git.

### Fixed
- Resolved issue where the updated git blacklist configuration would provide a false positive result
on functions ending with `add()` or `odd()` due to checks on dump and die `dd()` statements.

### Added
- Git blacklist now checks for `exit()` usage.

## 2.16.0
### Fixed
- Testing suite no longer breaks during installation when composer project type is set to `pimcore-project`.

### Added
- `phpcs.xml`, `phpmd.xml` and `phpstan.neon` files are now automatically installed in a local `pimcore-project` if they don't exist yet.

## 2.15.0
### Added
- Project type resolver can now look for pimcore projects.
  - Pimcore projects have their own [git blacklist](docs/components/git-blacklist.md) configuration.
  - In the future, the pimcore coding standard will have its own package for `phpcs.xml` and `phpmd.xml` rulesets.
- `grumphp.yml` file for `pimcore` projects.
  - This file falls back on the default configuration and inherits all properties, except for the blacklist triggers. 
- [Git blacklist](docs/components/git-blacklist.md) documentation.

### Changed
- The magento2 `grumphp.yml` file is split off from the default configuration.
  - The `grumphp.yml` that's part of a project will automatically point to the new magento2-specific config file.
  - The new file falls back on the default configuration, and overrides the git blacklist keywords and triggers.
  - The magento specific constructs are also removed from the default `grumphp.yml` template.
- The default `phpcs.xml` file now references a relative ruleset instead of an absolute path.

## 2.14.0
### Added
- New pathing for `phpcs.xml` file.
- Added updated code styling for project type `Magento2`.
- Support for `phtml` in project type `Magento2`. 
  The phtml files will only be checked based on Magento2 code standards.

### Changed
- Project type `default`/`magento` use correct index replaced `excludes_analyse` with `excludePaths` for `phpstan.neon` file.
- Project type `default` use correct tags for `phpunit_dist.xml`.
- Updated dependency to `youwe/composer-dependency-installer`

### Changed
- Phpcs will now also check files `phtml`. If not preferred behaviour overwrite
  parameter `phpcs.triggered_by` in `grumphp.yml` and change back to `[php]`.
- `.eslintrc.json` which was updated coherent to Fisheye configurations.

## 2.13.1 - 2022-08-03
### Fixed
- Github actions are now actually executed.

## 2.13 - 2022-08-02
### Changed
- Dependency `phpro/grumphp` now we prefer `phpro/grumphp-shim` which is a `phar`
  package which is not dependent on dependencies of the project the testing suite
  is installed in.

### Removed
- Extension Youwe\TestingSuite\Composer\GrumPHP\ParameterFixExtension since we now prefer 
  `phpro/grumphp-shim` over `phpro/grumphp`. This extension is used to resolve env
  variables in tasks and since we do not do that it's removed.

## 2.12 - 2022-05-30
### Added
- PHP 8 compatibility.
- GitHub Actions Workflow to run testing-suite for PHP 7.4, 8.0, and 8.1.

## 2.11.1 - 2022-04-10
### Changed
- Minimum stability to test require packages in a project.

## 2.11.0 - 2022-04-10
### Fixed
- Old pathing to Mediact pathing in files `phpcs.xml`, `phpmd.xml` & `grumphp.yml` will now be replaced by 
  Youwe pathing to prevent error of phpcs/phpmd/grumphp.

### Changed
- Versions of packages required are now not using `@stable` anymore.
- Versions of packages can now be updated.

## 2.10.0 - 2021-03-10
### Added
- Copyright.
- Declare strict type.

### Changed
- Vendor name from Mediact to Youwe.
- `grumphp.yml` now uses `securitychecker_enlightn` instead of `securitychecker` which caused an error with
  new version of grumphp.

### Removed
- bitbucket pipeline file. This will be added by a different module.
- Io class from configInstaller since it was never read.

## [2.9.1]
### Changed
- [AD-210] Run phpcs with the -s flag to output the explicit rule that is failing.
