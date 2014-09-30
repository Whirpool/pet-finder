<?php

class m140411_120608_imagetable extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_search_images', array(
                'id' => 'pk',
                'post_id' => 'int(11) UNSIGNED NOT NULL',
                'size' => 'int(11) NOT NULL',
                'source' => 'text NOT NULL',
                'mime' => 'text NOT NULL',
                'name' => 'text NOT NULL',
            ));
	}

	public function down()
	{
		echo "m140411_120608_imagetable does not support migration down.\n";
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