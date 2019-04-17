# Magento 2 DevFix Extension

## Install

Run the following command in Magento 2 root folder:

``` sh
composer require khoazero123/module-dev-fix
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

or

``` sh
mkdir -p app/code/Khoazero123/DevFix
git clone git@github.com:magento-module/module-dev-fix.git app/code/Khoazero123/DevFix
```