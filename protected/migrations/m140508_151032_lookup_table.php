<?php

class m140508_151032_lookup_table extends CDbMigration
{
	public function up()
	{
        $this->dropTable('tbl_age');
        $this->dropTable('tbl_pet');
        $this->dropTable('tbl_sex');

        $this->createTable('tbl_lookup', [
            'id' => 'pk',
                'type' => 'varchar(32) NOT NULL',
                'type_id' => 'tinyint NOT NULL',
                'name' => 'varchar(32) NOT NULL'
            ]);

        $this->insert('tbl_lookup', [
                'type' => 'pet',
                'type_id' => 1,
                'name' =>'Кошка',
            ]);
        $this->insert('tbl_lookup', [
                'type_id' => 2,
                'type' => 'pet',
                'name' =>'Собака',
            ]);

        $this->insert('tbl_lookup', [
                'type_id' => 1,
                'type' => 'age',
                'name' => 'до 1 года',
            ]);
        $this->insert('tbl_lookup', [
                'type_id' => 2,
                'type' => 'age',
                'name' => 'до 3 лет',
            ]);
        $this->insert('tbl_lookup', [
                'type_id' => 3,
                'type' => 'age',
                'name' => 'до 5 лет',
            ]);
        $this->insert('tbl_lookup', [
                'type_id' => 4,
                'type' => 'age',
                'name' => 'после 5 лет',
            ]);
        $this->insert('tbl_lookup', [
                'type_id' => 1,
                'type' => 'sex',
                'name' => 'мужской',
            ]);
        $this->insert('tbl_lookup', [
                'type_id' => 2,
                'type' => 'sex',
                'name' => 'женский',
            ]);
	}

	public function down()
	{
		echo "m140508_151032_lookup_table does not support migration down.\n";
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