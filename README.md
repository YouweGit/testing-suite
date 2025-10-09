[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mediact/testing-suite/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mediact/testing-suite/?branch=master)

# Youwe Testing Suite

## Introduction

Youwe Testing Suite is an all-in-one solution for doing static code analysis on 
composer packages/projects. It does so both locally and in CI/CD. For this,
Testing-suite leverages [GrumPHP](https://github.com/phpro/grumphp) with 
predefined default configurations per project type.

## Features

- [Project Type detector](docs/features/project-type-detection.md)
- [PHP storm configuration](docs/features/php-storm-integration.md)

## Supported project types

The Youwe Testing Suites offers pre-configured standards for the following project types.

| Testing Suite Project Type | Detected based on Composer Project Type                                    |
|----------------------------|----------------------------------------------------------------------------|
| `drupal`                   | `drupal-bundle`, `drupal-project`                                          |
| `magento2`                 | `magento-module`, `magento-project`, `magento2-module`, `magento2-project` |
| `pimcore`                  | `pimcore-bundle`, `pimcore-project`                                        |
| `default`                  | Any other                                                                  |

This project type is either detected from `composer.json` via the section

```json
{
    "extra": {
        "youwe-testing-suite": { "type": "magento2" }
    }
}
```

or if no explicit setting was found for the Testing Suite, via

```json
{
    "type": "magento2-module"
}
```

## Included analysis tools

- [Git blacklist](docs/components/git-blacklist.md)
- [Git commit message](docs/components/git-commit-message.md)
- [Composer file validation](docs/components/composer.md)
- [JSON Lint](docs/components/jsonlint.md)
- [YamlLint](docs/components/yamllint.md)
- [PHPCS (Coding Standards)](docs/components/phpcs.md)
- [PHPMD (Mess Detector)](docs/components/phpmd.md)
- [PHPStan (Find bugs before they reach production)](docs/components/phpstan.md)
- [PHPUnit (Execute Unit tests)](docs/components/phpunit.md)
- [PHP Lint](docs/components/phplint.md)
- [ESLint (Find and fix problems in your JavaScript code)](docs/components/eslint.md)
- [Enlighten Security Checker](docs/components/security-checker.md)

## Installation

First, configure your project type (see the above list of supported types) via
```
composer config type magento2-project
```
or
```
composer config extra.youwe-testing-suite.type magento2
```

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
or for DDev projects
```
ddev exec vendor/bin/grumphp run
```

### Git commit hook

The testing suite is automatically run at each git commit using a git
commit hook.

This git commit hook will automatically detect DDev projects and will
run GrumPHP within the DDev container if needed. This behaviour can
be disabled:

- on individual basis via an environment variable: 
```
YOUWE_BYPASS_DDEV=1 git commit
```
- on project basis via the `grumphp.yml` configuration file:
```
imports:
  - resource: 'vendor/youwe/testing-suite/config/your-project-type/grumphp.yml'

grumphp:
  git_hook_variables:
    # Disable DDev detection by restoring to default `exec`
    # See https://github.com/phpro/grumphp/blob/v2.x/doc/parameters.md for more options
    EXEC_GRUMPHP_COMMAND: exec
```
After changing your `grumphp.yml`, run `vendor/bin/grumphp git:init` to update the git commit hook.

### CI/CD Integration examples

- [Bitbucket Pipelines](docs/examples/bitbucket-pipelines.md)
- [GitHub Actions](docs/examples/github-actions.md)

## Changelog

See the [Changelog](CHANGELOG.md) file for all changes.
