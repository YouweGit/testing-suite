# GitHub Actions

The example below will run the testing-suite inside [github actions] (https://github.com/features/actions)
for both PHP 7.4 and 8.1.

```yml
name: Testing Suite
on: [push]
jobs:
  PHP:
    strategy:
      # Test with multiple PHP versions  
      matrix:
        image: [
          'srcoder/development-php:php74-fpm',
          'srcoder/development-php:php81-fpm'
        ]
    runs-on: ubuntu-latest
    container:
      image: ${{ matrix.image }}
    steps:
      # Checkout the repository
      - name: Checkout
        uses: actions/checkout@v2
      # Run Testing Suite
      - name: Testing Suite
        run: |
          composer2 install --dev --prefer-dist --no-scripts --no-progress --optimize-autoloader --no-interaction -vvv
          composer2 show
          composer2 exec -v grumphp run
        shell: bash

```
