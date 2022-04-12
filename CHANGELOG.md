# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
