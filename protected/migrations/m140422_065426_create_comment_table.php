<?php

class m140422_065426_create_comment_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_pf_comment',[
            'id' => 'pk',
                'post_id' => 'integer NOT NULL',
                'author' => 'integer NOT NULL',
                'time_create' => 'integer NOT NULL',
                'content' => 'text NOT NULL',
            ]);
        $this->createTable('tbl_pf_answers', [
                'id' => 'pk',
                'comment_id' => 'integer NOT NULL',
                'author' => 'integer NOT NULL',
                'time_create' => 'integer NOT NULL',
                'content' => 'text NOT NULL',
            ]);
	}

	public function down()
	{
		echo "m140422_065426_create_comment_table does not support migration down.\n";
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