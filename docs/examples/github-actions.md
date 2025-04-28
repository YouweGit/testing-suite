# GitHub Actions

The example below will run the testing-suite inside [github actions](https://github.com/features/actions)
for PHP versions 8.1 through 8.4 and specify whether PHPUnit should also be run depending on PHP version.

```yml
name: Testing Suite
on: [push]
jobs:
  PHP:
    strategy:
      matrix:
        php-version: [8.1, 8.2, 8.3, 8.4]
    runs-on: ubuntu-latest
    container:
      image: ${{ matrix.php-version == '8.1' && 'srcoder/development-php:php81-fpm' ||
        matrix.php-version == '8.2' && 'srcoder/development-php:php82-fpm' ||
        matrix.php-version == '8.3' && 'srcoder/development-php:php83-fpm' ||
        matrix.php-version == '8.4' && 'srcoder/development-php:php84-fpm' }}
    steps:
      - name: Checkout
        uses: actions/checkout@v2

      - name: Install Dependencies
        run: |
          composer2 install --dev --prefer-dist --no-scripts --no-progress --optimize-autoloader --no-interaction -vvv
          composer2 show
        shell: bash

      - name: Run GrumPHP Tasks
        run: |
          if [[ "${{ matrix.php-version }}" == "8.1" || "${{ matrix.php-version }}" == "8.2" ]]; then
            composer2 exec -v grumphp -- run --tasks=composer,jsonlint,xmllint,yamllint,phpcs,phplint,phpmd,phpstan,securitychecker_enlightn
          else
            composer2 exec -v grumphp -- run
          fi
        shell: bash


```
