<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace esoftslimited\settings\models;

/**
 * Interface SettingInterface
 * @package pheme\settings\models
 **/
 
interface ISettings
{

    
    //public function updateBatch($category,$args);
    /**
    * Returns 
    * @param $CKey //category.key   
    * @param $parent
    * @return Settings
    **/
    //public function getVar($Ckey,$parent=null);
    /**
     * Gets all settings of a given scope
     * @param $scope default systemwide;
     * @return array
     */
    public function getAll($category,$scope='custom|systemwide',$parent=null);
    /**
    * get Setting
    **/
    public function get($category, $key,$creator=null,$parent=null);
    /**
     * Saves a setting
     *
     * @param $category
     * @param $key
     * @param $value
     * @param $type
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function set($category, $key, $value, $type,$scope='custom',$options=[]);

    /**
     * Deletes a settings
     *
     * @param $key
     * @param $category
     * @return boolean True on success, false on error
     */
    public function deleteConfig($category, $key);

    /**
     * Deletes all settings! Be careful!
     * @return boolean True on success, false on error
     */
    public function deleteAllConfig();

    /**
     * Activates a setting
     *
     * @param $key
     * @param $category
     * @return boolean True on success, false on error
     */
    public function activate($category, $key);

    /**
     * Deactivates a setting
     *
     * @param $key
     * @param $category
     * @return boolean True on success, false on error
     */
    public function deactivate($category, $key);
    /**
     * Set scope of a setting
     *
     * @param $key
     * @param $scope
     * @return boolean True on success, false on error
     */
    public function setScope($key,$scope,$owner=null);
    /**
     * Set parent of a setting if set the scope is saved as 'custom' accessible only by creator
     *
     * @param $key
     * @param $scope
     * @return boolean True on success, false on error
     */
    public function setParent($key,$parent);

}