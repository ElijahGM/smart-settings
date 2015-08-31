<?php

namespace esoftslimited\settings;

/**
 * A module for managing system settings
 **/
class Module extends \yii\base\Module
{
	//model map
	public $_modelMap;
	//var default setting scope
	public $defaultScope='system_wide';

    public function init()
    {
        
    }
}
