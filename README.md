[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mediact/testing-suite/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mediact/testing-suite/?branch=master)

# Youwe Testing Suite

## Introduction

Youwe Testing Suite is an all-in-one solution for doing static code analysis on 
composer packages/projects. It does so both locally and in CI/CD. For this,
Testing-suite leverages [GrumPHP](https://github.com/phpro/grumphp) with 
predefined default configurations per project type.

## Features

- [Project Type detector](docs/features/project-type-detector.md)
- [PHP storm configuration](docs/features/php-storm-integration.md)

## Supported project types

- Default (`default`)
- Laravel (`laravel`)
- [Magento 1](docs/project-types/magento1.md) (`magento1`)
- [Magento 2](docs/project-types/magento2.md) (`magento2`)
- Pimcore (`pimcore`)

## Included analysis tools

- [Git blacklist](docs/components/git-blacklist.md)
- [Composer file validation](docs/components/composer.md)
- [JSON Lint](docs/components/jsonlint.md)
- [YamlLint](docs/components/yamllint.md)
- [Coding Standards (PHPCS)](docs/components/phpcs.md)
- [Mess Detector (PHPMD)](docs/components/phpmd.md)
- [Find bugs before they reach production (PHPStan)](docs/components/phpstan.md)
- [Execute Unit tests (PHPUnit)](docs/components/phpunit.md)
- [PHP Lint](docs/components/phplint.md)
- [Find and fix problems in your JavaScript code (ESLint)](docs/components/eslint.md)
- [Enlighten Security Checker](docs/components/security-checker.md)

## Installation

Testing suite is supposed to be installed as a composer `dev` dependency.
Within any project just run the command below to install the package:
```
composer require youwe/testing-suite --dev
```
If a project-type is detected, standards will be applied (otherwise a wizard will
be opened)

## Usage

### Locally

The testing suite can be run manually through the GrumPHP command.

```
vendor/bin/grumphp run
```

The testing suite is also automatically run at each git commit using a git
commit hook.

### CI/CD Integration examples

- [Bitbucket Pipelines](docs/examples/bitbucket-pipelines.md)
- [GitHub Actions](docs/examples/github-actions.md)

## Changelog

See the [Changelog](CHANGELOG.md) file for all changes.
