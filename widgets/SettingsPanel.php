<?php
namespace esoftslimited\settings\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Widget;
use yii\bootstrap\Nav;
use kartik\widgets\DatePicker;

class SettingsPanel extends Widget
{
    public $category;
    public $selected;
    public $scope='custom';
    public $parent=null;
    public $action='update';
    public $template='<div class="box box-warning">
                <div class="box-header with-border">
                  
                </div><!-- /.box-header -->
                <div class="box-body"><form method="post" action="{action}" class="" role="form">{nonce}{input}</form></div></div>';
    public $input_template='<div class="form-group">
                              {label}
                              {input}
                            </div>{separator}';
    public $checkboxTemplate='<div class="form-group">
                                <div class="checkbox">
                                  <label>
                                   {input}
                                  </label>
                                </div>
                              </div>';
    public $checkboxOptions=['class'=>''];
    public $radioTemplate='<div class="radio">
                            <label>
                              {input}
                              {labelText}
                            </label>
                          </div>';
    public $radioOptions=['class'=>''];
    public $radioCallback=false;
    public $buttonOptions=['class'=>'btn btn-primary btn-block'];
    public $addSeparator=true;
    /**
     * Renders the widget.
     **/
    public function run()
    {        
      return $this->renderFields();
    }
    private function genField($name,$setting,$options){
     
        if(preg_match("/number|string/i", $setting->type)):
            return Html::textInput($name,$setting->value,$options);
        elseif(preg_match("/text/i", $setting->type)):
            return Html::textArea($name,$setting->value,$options);  

        elseif(preg_match("/bool/i", $setting->type)):

            return Html::checkBox($name,$setting->value,array_merge($options,$this->checkboxOptions));
        elseif(preg_match("/dropdown/i", $setting->type)):
            
            return Html::dropDownList($name,$setting->value,unserialize($setting->options),$options);
        elseif(preg_match("/radiolist/i", $setting->type)):
            $data=unserialize($setting->options);
            $template=$this->radioTemplate;
            $this->radioOptions['item']=(!$this->radioCallback)?function($index, $label, $name, $checked, $value)use ($template){
                 
                      return strtr($template,['{input}'=>Html::radio($name, $checked, ['value'  => $value]),'{labelText}'=>$label]);
            }:$this->radioCallback;

            return Html::radioList($name,$setting->value,$data,array_merge($options,$this->radioOptions));
        elseif(preg_match("/{dateradiolist}/i", $setting->type)):
            $data=unserialize($setting->options);
            $template=$this->radioTemplate;
            $this->radioOptions['item']=function($index, $label, $name, $checked, $value)use ($template){
                 
                      return strtr($template,['{input}'=>Html::radio($name, $checked, ['value'  => $value]),'{labelText}'=>date($value,time())]);
            };

            return Html::radioList($name,$setting->value,array_combine($data,$data),array_merge($options,$this->radioOptions));
        elseif(preg_match("/timezone/i", $setting->type)):
            
            return Html::dropDownList($name,$setting->value,$this->getTimezones(),$options);                
        elseif(preg_match("/date/i", $setting->type)):
            return  DatePicker::widget([
                        'name' => $name,
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => date('d-m-Y',strtotime($setting->value)),
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-M-yyyy'
                        ]
                    ]);
        else:
            return Html::textArea($name,$setting->value,$options);;
        endif;
     
    }
    public function renderFields(){
     $modelClass=\Yii::$app->settings->modelClass;
     echo Nav::widget([
            'options' => [
                'class' => 'nav-tabs',
                'style' => 'margin-bottom: 15px'
            ],
            'items' =>array_values($this->category),
            ]);
     $models=Yii::$app->settings->getAll($this->selected,$this->scope,$this->parent);

     if($models){
        $input="";
        foreach ($models as $category => $setting) {
          

          $name=strtr("{category}[{key}]",['{category}'=>$setting->category,'{key}'=>$setting->key]);
          $options=['class'=>"form-control",'id'=>$setting->key];
          $input.=strtr($this->input_template,[

             '{input}'=>$this->genField($name,$setting,$options),
             '{label}'=>Html::label((!is_null($setting->label))?ucfirst($setting->label):ucfirst($setting->key),$setting->key),
             '{separator}'=>($this->addSeparator)?"<hr/>":"",
            ]); 
                
        }
        $input.=strtr($this->input_template,[

            '{input}'=>Html::submitButton('Configure',$this->buttonOptions),
            '{label}'=>'',
            '{separator}'=>'',
            ]);

        echo strtr($this->template,['{action}'=>\yii\helpers\Url::to([$this->action]),'{nonce}'=>Html::hiddenInput('_csrf',Yii::$app->request->CsrfToken),'{input}'=>$input]);
     }else{
        echo "No settings associated with $this->selected found";
     }
     
    }
    private function getTimezones(){
        $timeZones = [];
        $timeZonesOutput = [];
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
          array_multisort($timeZones);
        foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::ALL) as $timeZone) {
            $now->setTimezone(new \DateTimeZone($timeZone));
            $timeZones[] = [$now->format('P'), $timeZone];
        }

        
            array_multisort($timeZones);
        
        foreach ($timeZones as $timeZone) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) use ($timeZone) {
                switch ($matches[0]) {
                    case '{name}':
                        return $timeZone[1];
                    case '{offset}':
                        return $timeZone[0];
                    default:
                        return $matches[0];
                }
            }, '{name} {offset}');
            $timeZonesOutput[$timeZone[1]] = $content;
        }
        return $timeZonesOutput;
    }

}