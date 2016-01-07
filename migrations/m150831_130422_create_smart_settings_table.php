<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_130422_create_smart_settings_table extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%settings}}',
            [
                'id'         => Schema::TYPE_PK,
                'type'       => Schema::TYPE_STRING,
                'category'   => Schema::TYPE_STRING, //category of this information
                'key'        => Schema::TYPE_STRING, //unique
                'value'      => Schema::TYPE_TEXT,
                'parent'     => Schema::TYPE_INTEGER, //can be used in multitenacy applicatin where parent = company
                'label'      => Schema::TYPE_STRING, //label of this category
                'options'    => Schema::TYPE_TEXT, //other availabe opptions for this settings
                'scope'      => Schema::TYPE_TEXT, //scope of this settings custom|ssytem wide
                'active'     => Schema::TYPE_BOOLEAN,
                'created_by' => Schema::TYPE_DATETIME,
                'created_at' => Schema::TYPE_DATETIME,
                'modified_at'=> Schema::TYPE_DATETIME,
            ]
        );
    }

    public function down()
    { 
        // dont allow destroying table once created
        echo "settings table cannot be reverted.\n";

        return false;
    }
}
