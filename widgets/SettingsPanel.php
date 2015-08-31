<?php
namespace esoftslimited\settings\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Widget;
use yii\bootstrap\Nav;


class SettingsPanel extends Widget
{
	public $sections;
	public $selected;
    public $template='<div class="box box-warning">
                <div class="box-header with-border">
                  
                </div><!-- /.box-header -->
                <div class="box-body"><form method="post" class="" role="form">{input}</form></div></div>';
    public $input_template='<div class="form-group">
		                      {label}
		                      {input}
		                    </div>';
    public $checkboxTemplate='<div class="form-group">
		                        <div class="checkbox">
		                          <label>
		                           {input}
		                          </label>
		                        </div>
		                      </div>';
    public $checkboxOptions=['class'=>''];
    public $buttonOptions=['class'=>'btn btn-primary btn-block'];
    /**
     * Renders the widget.
     **/
    public function run()
    {        
      return $this->renderFields();
    }
    private function genField($type,$value=null,$name,$options){
     
     	if(preg_match("/number|string/i", $type)):
     		return Html::textInput($name,$value,$options);
     	elseif(preg_match("/text/i", $type)):
     		return Html::textArea($name,$value,$options);	

     	elseif(preg_match("/bool/i", $type)):

     		return Html::checkBox($name,$value,array_merge($options,$this->checkboxOptions));  	
     	else:
     		return Html::textArea($name,$value,$options);;
     	endif;
     
    }
    public function renderFields(){
     $modelClass=\Yii::$app->settings->modelClass;
     echo Nav::widget([
		    'options' => [
		        'class' => 'nav-tabs',
		        'style' => 'margin-bottom: 15px'
		    ],
		    'items' =>array_values($this->sections),
		    ]);
     $models=$modelClass::find()->where(['section'=>$this->selected])->andWhere(['active'=>1])->all();

     if($models){
     	$input="";
     	foreach ($models as $section => $setting) {
     	  

     	  $name=strtr("{section}[{key}]",['{section}'=>$setting->section,'{key}'=>$setting->key]);
     	  $options=['class'=>"form-control",'id'=>$setting->key];
     	  $input.=strtr($this->input_template,[
     	  	 '{input}'=>$this->genField($setting->type,$setting->value,$name,$options),
             '{label}'=>Html::label(ucfirst($setting->key),$setting->key)
     	  	]); 
     	  		
     	}
     	$input.=strtr($this->input_template,[
     		'{input}'=>Html::submitButton('Configure',$this->buttonOptions),
     		'{label}'=>'',

     		]);

     	echo strtr($this->template,['{input}'=>$input]);
     }else{
     	echo "No settings associated with $this->selected found";
     }
     
    }


}