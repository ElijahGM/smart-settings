<?php
namespace esoftslimited\settings\components;

use Yii;
use yii\caching\Cache;
use yii\base\Component;

/**
 * @author Elijah Mwangi <emwangi.g@gmail.com>
 */
class Settings extends Component
{ 
	public $defaultScope="system_wide";
	
	//settings model class
	public $modelClass="esoftslimited\settings\models\Settings";

	public function init(){
		parent::init();
       
	}
	/**
	* update setting multiple Settings
	* @param $Ckey compound key derived from category.key
	* @param $value value of the settings
	* @param $type type of the settings
	* @param $scope of the settings custom|system_wide
	**/
	public function updateBatch($category,$args){
       //exit(print_r($args,1));
      foreach ($args as $key => $value) {
      	$model=$this->get($category.".".$key); 
      	 if($model)
      	 $model->value=$value;
      	 $model->save(); 
      }     
       	 	
       return false;
	}
	/**
	* update setting elemnt safely
	* @param $Ckey compound key derived from category.key
	* @param $value value of the settings
	**/
	public function update($Ckey,$value){
      
       $model=$this->get($Ckey);       
       if($model)
       	  $model->value=$value;
       	 if($model->save())
       	 	return false;
       	 	
        return false;
	}
	/**
	* Set setting 
	* @param $Ckey compound key derived from category.key
	* @param $value value of the settings
	* @param $type type of the settings
	* @param $scope of the settings custom|system_wide
	**/
	public function set($Ckey,$value,$type=SETTINGS_TEXT,$scope=null,$options=[]){
     
       list($category,$key)=explode(".", $Ckey);
       $model=new $this->modelClass;
       $scope=(is_null($scope) )?$this->defaultScope:'system_wide';
       if($model)
       	$model->set($category, $key, $value, $type,$scope,$options);
       	return $model->save();
       return false;
	}
    /**
	* @param $CKey //category.key	
	* @param $parent
	* @return Settings
	**/
	public function get($Ckey,$parent=null){
		list($category,$key)=explode(".", $Ckey);
		$model=new $this->modelClass;
        if($model)
       	  return $model->get($category, $key, $parent);       	
        return null;
	}
	/**
	* @param $CKey //category.key	
	* @param $parent
	* @return Settings
	**/
	public function getVar($Ckey,$parent=null){
		list($category,$key)=explode(".", $Ckey);
		$model=$this->get($Ckey,$parent);
        if($model)
       	  return $model->value;       	
        return null;
	}
	/**
	* @param $category
	* @param $scope
	* @param $parent
	* @return Settings[]
	**/
	public function getAll($category,$scope="system_wide",$parent=null){
       $model=new $this->modelClass;
        if($model)
       	  return $model->getAll($category,$scope,$parent);       	
        return [];
	}
    
}