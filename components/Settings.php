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
	public function saveSettings($setting){

		
		$model=$this->get($setting['category'].".".$setting['key'],$setting['created_by']);
		if(!$model){
          $model=new $this->modelClass;
         
		}
		$model->detachBehavior('blameable');
		$model->detachBehavior('timestamp');
		if($model->load([$model->formName()=>$setting]) && $model->save(false)){
	      return $model->id;
		}
		return 1;
	}

	public function addBulksettings($configuration,$parent=0,$created_by=null,$scope="custom"){

		foreach ($configuration as $category => $setting){
			$list=explode(".", $category);
	       if(count($list)<2){
	          $setting['category']=$category;
	          $setting['key']=$category;
	       }else{
	       	  list($cat,$key)=$list;
	       	  $setting['category']=$cat;
	          $setting['key']=$key;
	       }
	      
	       if(!is_null($created_by)){
	       	  $setting['created_by']=$setting['updated_by']=$created_by;
	       
	       }elseif(!Yii::$app->user->isGuest){
	          $setting['created_by']=$setting['updated_by']=Yii::$app->user->id;
	       }else{
	          $setting['created_by']=$setting['updated_by']=0;
	       }

	       $setting['scope']=$scope;
	       $setting['parent']=$parent;
	       $setting['options']=serialize($setting['options']);
	       $setting['updated_at']=$setting['created_at']=date('Y-m-d H:i:s',time());

		   $parent_id=$this->saveSettings($setting);
		   if(isset($setting['children']) && is_array($setting['children'])){
		   	 
		   	$this->addBulksettings($setting['children'],$parent_id,$setting['created_by'],$scope);
		   }
		}
	}

	/**
	* update setting multiple Settings
	* @param $Ckey compound key derived from category.key
	* @param $value value of the settings
	* @param $type type of the settings
	* @param $scope of the settings custom|system_wide
	**/
	public function updateBatch($category,$args,$creator=null){
      $res=false;
      
      foreach ($args as $key => $value) {
      	$res=$this->update($category.".".$key,$value,$creator);     	
      }     
       	 	
       return $res;
	}
	/**
	* update setting elemnt safely
	* @param $Ckey compound key derived from category.key
	* @param $value value of the settingsowner of the dettings
	* @param $creator 
	**/
	public function update($Ckey,$value,$creator=null){
       
       $model=$this->get($Ckey,$creator);       
       if($model){
       	    // for checkboxes
      	 	if(preg_match("/bool/i", $model->type) && !isset($_REQUEST[$model->category][$model->key])){
      	 		 $model->value=0;
      	 	}else{
      	 		 $model->value=$value;
      	    }
      	
      	 return $model->save(); 
      	}
       	 	
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
	public function get($Ckey,$creator=null,$parent=null){
		list($category,$key)=explode(".", $Ckey);

		$model=new $this->modelClass;
        if($model)
       	  return $model->get($category, $key, $creator,$parent);       	
        return null;
	}
	/**
	* @param $CKey //category.key	
	* @param $args
	* @param $parent
	* @return Settings
	**/
	public function getVar($Ckey,$args=[],$creator=null,$parent=null){
		
		$model=$this->get($Ckey,$creator,$parent);
        if($model)
       	  return !empty($args)?Yii::t('app',$model->value,$args):$model->value;       	
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