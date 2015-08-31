Smart Settings
==============
A cool plugin that is extensible that add support for addition of system wide settings into your application

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist esoftslimited/smart-settings "*"
```

or add

```
"esoftslimited/smart-settings": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

## COnfiguration
Add the following to your confi file under component section

```php 
  'components'=>[
    'class'=>' \esoftslimited\settings\components\Setting',

   ],
  ...
  ?>
```

#Migration
Run the following commond in your terminal to install latest database
```
 
 $ php yii migrate --migrationPath=@vendow/esoftslimited/settings/migrations --interactive=1

```