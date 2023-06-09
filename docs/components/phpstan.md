# Static code analysis (PHPStan)

Find bugs before they reach production.
Static code analysis is executed using PHPStan. A file called `phpstan.neon`
is added during the installation of the testing suite and can be customized per 
project / repository.

[PHPStan](https://phpstan.org/)

## Tips & Tricks

First of all, there is always a reason some code should be ignored. Please make 
sure to always document that reason, either in a comment or a commit message.

You can work around this by ignoring these in `phpstan.neon`

### PHPStan will not recognize magic methods, e.g.

```txt
------ ------------------------------------------------------------------------------------- 
  Line   app/code/YourProject/Rma/ViewModel/Guest.php                                          
 ------ ------------------------------------------------------------------------------------- 
  130    Call to an undefined method Magento\Framework\App\RequestInterface::getPostValue().  
 ------ ------------------------------------------------------------------------------------- 
```

The best way to work around this would be adding this entry to `phpstan.neon`

```txt
    - message: '#Call to an undefined method Magento\\Framework\\App\\RequestInterface::getPostValue\(\)#'
      path: app/code/YourProject/Rma/ViewModel/Guest.php
```

A less sophisticated workaround would be ignoring a single line. Make sure to add 
a comment though, so others are aware of what exactly should be ignored. 
Unfortunately PHPStan does not support ignoring specific errors in inline comments.

```php
<?php
/** @phpstan-ignore-next-line Ignore magic method getPostValue */
$post = $request->getPostValue();
```

## Magento 2

For Magento 2 projects the [BitExpert PHPstan-magento](https://github.com/bitExpert/phpstan-magento#bitexpertphpstan-magento)
module is installed. Check out this page for a full list of [features](https://github.com/bitExpert/phpstan-magento/blob/master/docs/features.md).
