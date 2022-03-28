# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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