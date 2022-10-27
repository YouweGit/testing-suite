# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
- GitHub Actions Workflow to run testing-suite for PHP 7.4, 8.0 and 8.1.

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
