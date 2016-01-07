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

Import Database
-----

Configuration
-----

Once the extension is installed, simply use it in your code by  :

## Configuration
Add the following to your configuration file under component section

```php 
  'components'=>[
    'settings'=>['class'=>' \esoftslimited\settings\components\Settings']

   ],
  ...
  ?>
```

#Migration
Run the following commond in your terminal to install latest database
```
 
 $ php yii migrate --migrationPath=@vendor/esoftslimited/smart-settings/migrations --interactive=1

```

View Management
-----
#SettingPanel Widget
This module have a robust view components which autogenerates settings views/forms. The widget is configurable and flexible and can be used almost anywhere
```
esoftslimited\settings\widgets\SettingsPanel;
```
#Using the widget on Views
Just pass configuration to the SettingsPanel
```
<?=\esoftslimited\settings\widgets\SettingsPanel::widget([
	'category'=>[], //categories of setting to be tabbed
	'selected'=>'category.name',//current active categry
	'scope'=>'custom|system_setting',//current scope
	'parent'=>0// parent object
	/* More Custom settings*/
	/*'template'
	'input_template'
	'checkboxTemplate'
	'checkboxOptions'
	'buttonOptions'=>[]//default submit settings panel
	'addSeparator'=>true;*/

	])
?>
```
#Saving and updating Settings Value
The module come with add on actio for any controller implementing it for ease of updating and configuring settings
In your settings Controller 
```
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            ----
            'update'=>[
              'class'=>'\esoftslimited\settings\actions\SettingsAction',
              'view'=>'index',//custom view goes here
            ],
        ];
    }
```