<?php
namespace esoftslimited\settings;

use Yii;

use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;
use yii\console\Application as ConsoleApplication;
use yii\web\User;
define("SETTINGS_TEXT", "string", true);
define("SETTINGS_NUMBER","number", true);
define("SETTINGS_CHECKBOX","bool", true);
define("SETTINGS_RADIO","radio", true);
define("SETTINGS_DROPDOWN","dropdown", true);
define("SETTINGS_TEXT_AREA","text", true);
define("SETTINGS_DATE","date", true);
define("SETTINGS_TIME","time", true);
define("SETTINGS_DATE_TIME","datetime", true);

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Elijah Mwangi <emwangi.g@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @var inheritdoc */
    
    /** @var array Model's map */
    private $_modelMap;
   
    
    /** @inheritdoc */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application){
           $app->setComponents(['settings'=>['class'=>'esoftslimited\settings\components\Settings']]);

          
        }
        if (!isset($app->get('i18n')->translations['settings*'])) {
                $app->get('i18n')->translations['settings*'] = [
                    'class'    => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }
    }
}