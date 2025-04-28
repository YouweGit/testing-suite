# Project Type Detection
The type for a project can be overridden in the composer.json `config` node by
adding `testing-suite-type` to the configuration.
This will allow the use of standards for a different type.

Full list of supported project types can be found [here](../../README.md#supported-project-types)

The configurations can be set like this:
```json
{
  "config": {
    "youwe-testing-suite": {
      "type": "magento2"
    }
  }
}
```

The project type is also used to determine which rulesets to load, additional
package dependencies to install upstream, or how to update references upstream after
a ruleset change.
