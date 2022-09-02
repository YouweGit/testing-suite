# Project Type Detector

@TODO: rewrite this section.

The type for a project can be overridden in the composer.json `config` node by
adding `testing-suite-type` to the configuration.
This will allow the use of standards for a different type.

Full list of supported project types can be found [over here](../../README.md#supported-project-types)

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

For Magento Projects this can be helpful when development is done in the `app/code`
folder and force the testing suite to automatically select the correct standards.
