<?php

namespace esoftslimited\settings\models;

use Yii;
use esoftslimited\settings\models\ISettings;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "{{%sm_settings}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $category
 * @property string $key
 * @property string $value
 * @property integer $parent
 * @property string $label
 * @property string $options
 * @property string $scope
 * @property integer $active
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_at
 */
class Settings extends ActiveRecord implements ISettings
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value', 'scope'], 'required'],            
            [['value', 'options', 'scope'], 'string'],
            [['parent', 'active'], 'integer'],
            [['created_by','updated_by', 'created_at', 'modified_at'], 'safe'],
            [['type', 'category', 'key', 'label'], 'string', 'max' => 255]
        ];
    }
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_at',
                ],
                'value' => new Expression('NOW()'),
            ],
           'blameable'=>[
                      'class' => BlameableBehavior::className(),
                      'createdByAttribute' => 'created_by',
                      'updatedByAttribute' => 'updated_by',
                  ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('settings', 'ID'),
            'type' => Yii::t('settings', 'Type'),
            'category' => Yii::t('settings', 'Category'),
            'key' => Yii::t('settings', 'Key'),
            'value' => Yii::t('settings', 'Value'),
            'parent' => Yii::t('settings', 'Parent'),
            'label' => Yii::t('settings', 'Label'),
            'options' => Yii::t('settings', 'Options'),
            'scope' => Yii::t('settings', 'Scope'),
            'active' => Yii::t('settings', 'Active'),
            'created_by' => Yii::t('settings', 'Created By'),
            'updated_by' => Yii::t('settings', 'Updated By'),
            'created_at' => Yii::t('settings', 'Created On'),
            'modified_at' => Yii::t('settings', 'Modified On'),
        ];
    }

    /**
     * @inheritdoc
     * @return SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }
    /**
     * @inheritdoc
     * @return array of settings in a scope.
     */
    public function getAll($category,$scope='system_wide',$owner=null){
 
     return $this->find()->scope($scope)->category($category)->parent($owner)->all();
    }
    function loadModel($category, $key){
      
    }
    /**
     * @inheritdoc
     * @return settings in a scope.
     */
    public function get($category, $key,$parent=null){
      if(!is_null($parent)){
        return $this->find()->category($category)->where(['key'=>$key,'parent'=>$parent])->one();
      }
      return $this->find()->category($category)->where(['key'=>$key])->one();
    }
    public function set($category, $key, $value, $type,$scope='system_wide',$options=[]){
      $owner=@$options['owner'];
      $label=@$options['label'];
      $data =(@isset($options['data']))?@$options['data']:[];
      $attributes=['Settings'=>[
                   'category'=>$category,
                   'key'=>$key,
                   'value'=>$value,
                   'type'=>$type,
                   'scope'=>$scope,
                   'parent'=>$owner,
                   'options'=>serialize($data),
                   'active'=>1,
                   'label'=>@$options['label']

                   ]];
      $model=$this->find()->category($category)->where(['key'=>$key])->one();
      if(!$model) $model=$this;  
      
      $model->load($attributes);

      if(!$model->save()){
        throw new \Exception("Error Saving settings ".print_r($this->errors,1), 1);
        
      }
      return true;
    }
    public function deleteConfig($category, $key){

    }
    public function deleteAllConfig(){

    }
    public function activate($category, $key){

    }
    public function deactivate($category, $key){

    }
    public function setScope($key,$scope,$owner=null){

    }
    public function setParent($key,$parent){

    }


}
