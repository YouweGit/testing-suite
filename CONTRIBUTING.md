Before opening a PR with changes, make sure all the linting steps are successful.

The require-dev dependency for phpunit is set to @stable for the github actions, but the tests themselves\
assume they are running against PHPUnit 12 and php >= 8.3. The github actions only run phpunit tests against\
a php 8.3 container.

If a PR is approved please ask one of the following maintainers to get it merged:

- [Igor Wulff](https://github.com/igorwulff)
- [Leon Helmus](https://github.com/leonhelmus)
- [Rutger Rademaker](https://github.com/rutgerrademaker)
