<?php

class m140118_133906_main_search_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_pet_finder', array(
                'id' => 'pk',
                'pet_id' => 'tinyint(2) UNSIGNED NOT NULL',
                'breed' => 'string NOT NULL',
                'age_id' => 'tinyint(2) UNSIGNED NOT NULL',
                'name' => 'string',
                'sex' => 'tinyint(1) UNSIGNED NOT NULL',
                'special' => 'text',
                'advanced' => 'text',
                'date' => 'integer NOT NULL',
                'contact' => 'string',
                'date_create' => 'integer NOT NULL',
                'date_update' => 'integer NOT NULL',
                'search_type' => 'tinyint(1) NOT NULL',
                'search_status' => 'tinyint(1) NOT NULL DEFAULT 1',
                'author_id' => 'integer NOT NULL',
                'lng' => 'float NOT NULL',
                'lat' => 'float NOT NULL'
            ));
	}

	public function down()
	{
		$this->dropTable('tbl_pet_finder');
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