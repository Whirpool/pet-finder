<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=staytest',
                'emulatePrepare' => true,
                'username' => 'admin',
                'password' => 'mypass',
                'charset' => 'utf8',
                'tablePrefix' => 'tbl_',
            ),
		),
	)
);
