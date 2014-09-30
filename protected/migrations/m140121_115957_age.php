<?php

class m140121_115957_age extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_age', [
                'id' => 'pk',
                'age' => 'string',
            ]);
	}

	public function down()
	{
		echo "m140121_115957_age does not support migration down.\n";
		return false;
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