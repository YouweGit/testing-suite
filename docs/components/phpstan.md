# Static code analysis (PHPStan)

Find bugs before they reach production.
Static code analysis is executed using PHPStan. A file called `phpstan.neon`
is added during the installation of the testing suite and can be customized per 
project / repository.

[PHPStan](https://phpstan.org/)

## Tips & Tricks

First of all, there is always a reason some code should be ignored. Please make 
sure to always document that reason, either in a comment or a commit message.

### PHPStan will fail if generated classes are not generated.

For Magento 2 we work around this by ignoring these in `phpstan.neon`

```yaml
ignoreErrors:
  # Ignore errors in generated classes  
  - '#(class|type) Magento\\TestFramework#i'
  - '#(class|type) Magento\\\S*Factory#i'
  - '#(class|type) Magento\\\S*Interceptor#i'
  - '#(class|type) Magento\\\S*ExtensionInterface#i'
  - '#(class|type) Magento\\\S*Proxy#i'
```

### PHPStan will not recognize magic methods, e.g.

```sh
------ ------------------------------------------------------------------------------------- 
  Line   app/code/YourProject/Rma/ViewModel/Guest.php                                          
 ------ ------------------------------------------------------------------------------------- 
  130    Call to an undefined method Magento\Framework\App\RequestInterface::getPostValue().  
 ------ ------------------------------------------------------------------------------------- 
```

This can be worked around by ignoring a single line, make sure to add a comment 
though, so others are aware of what exactly should be ignored. Unfortunately
PHPStan does not support ignoring specific errors in inline comments.

```php
<?php
/** @phpstan-ignore-next-line Ignore magic method getPostValue */
$post = $request->getPostValue();
```
