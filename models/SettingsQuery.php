<?php

namespace esoftslimited\settings\models;

/**
 * This is the ActiveQuery class for [[Settings]].
 *
 * @see Settings
 */
class SettingsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['active'=>1]);
        return $this;
    }
    public function scope($scope='system_wide')
    {
        $this->andWhere(['scope'=>$scope]);
        return $this;
    }
    public function category($category)
    {
        $this->andWhere(['category'=>$category]);
        return $this;
    }
    public function parent($parent=null)
    {
        if(is_null($parent)) return $this;
        $this->andWhere(['parent'=>$parent]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return Settings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Settings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}