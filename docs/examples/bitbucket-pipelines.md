## Bitbucket Pipelines

When the project is hosted on Bitbucket [Pipelines](https://bitbucket.org/product/features/pipelines) 


The scripts supports a callback that will be called before
`composer install` is executed. This callback can be used to add credentials
to composer. To enable the callback go to **Bitbucket Settings > Pipelines >
Environment Variables** and add an environment variable called
`COMPOSER_PRE_INSTALL_CALLBACK`.

Example to add basic authentication for repo.example.com:

```
composer config --global http-basic.repo.example.com $YOUR_USER $YOUR_PASSWORD
```
