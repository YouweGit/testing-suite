# Run unit tests.

test ! -f phpunit.xml \
|| test ! -x $BIN/phpunit \
|| $BIN/phpunit \
		--fail-on-warning \
		--disallow-test-output \
		--report-useless-tests

# Require the coding standards, if they are not explicitly defined.
test -d $VENDOR/mediact/coding-standard \
|| composer require mediact/coding-standard \
		--dev \
		--prefer-dist \
		--no-scripts \
		--ignore-platform-reqs \
		--no-progress \
		--optimize-autoloader \
		--no-interaction

# Run static code analysis.
- if [ -d src ]; then test -f phpcs.xml && $BIN/phpcs src; fi
- if [ -d src ]; then test -f phpmd.xml && $BIN/phpmd src xml phpmd.xml; fi
- if [ -d src ]; then $BIN/phpstan analyse src --level 4 --no-progress; fi

- if [ -d tests ]; then test -f phpcs.xml && $BIN/phpcs tests; fi
- if [ -d tests ]; then test -f phpmd.xml && $BIN/phpmd tests xml phpmd.xml; fi
- if [ -d tests ]; then $BIN/phpstan analyse tests --level 4 --no-progress; fi
