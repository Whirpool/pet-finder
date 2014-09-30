<?php

class m140121_120307_insert_data extends CDbMigration
{
	public function up()
	{
        $this->insert('tbl_pet', [
                'id' => 1,
                'name' =>'Кошка',
            ]);
        $this->insert('tbl_pet', [
                'id' => 2,
                'name' =>'Собака',
            ]);

        $this->insert('tbl_age', [
                'id' => 1,
                'age' => 'до 1 года',
            ]);
        $this->insert('tbl_age', [
                'id' => 2,
                'age' => 'до 3 лет',
            ]);
        $this->insert('tbl_age', [
                'id' => 3,
                'age' => 'до 5 лет',
            ]);
        $this->insert('tbl_age', [
                'id' => 4,
                'age' => 'после 5 лет',
            ]);
	}

	public function down()
	{
		echo "m140121_120307_insert_data does not support migration down.\n";
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