<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
            'db'=>array(
                'connectionString' => 'pgsql:host=localhost;port=5432;dbname=pettest',
                'emulatePrepare' => true,
                'username' => 'whirpl',
                'password' => 'cegthgegth',
                'charset' => 'utf8',
            ),
		),
	)
);
