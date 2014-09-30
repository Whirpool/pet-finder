<?php

class m140121_115921_pet extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_pet', [
               'id' => 'pk',
                'name' => 'string',
            ]);
	}

	public function down()
	{
        $this->dropTable('tbl_pet');
    }

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}