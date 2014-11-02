<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array(
        'log',
        'debug',
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
        'application.helpers.*',
		'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

        'user' => array(
            'sendActivationMail' => false,
            'returnUrl' => array('/user/login'),
            'returnLogoutUrl' => array('/user/login'),
        ),

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'debug' => array(
            'class' => 'ext.debug.Yii2Debug',
        ),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=> false,
			'rules'=>array(
                array('pet/index',    'pattern'=>''),
                array('pet/relation', 'pattern'=>'api/relation',     'verb'=>'GET'),
                array('pet/view',     'pattern'=>'api/pet',          'verb'=>'GET'),
                array('pet/search',   'pattern'=>'api/pet/search',   'verb'=>'POST'),
                array('pet/new',      'pattern'=>'api/pet',          'verb'=>'POST'),

                array('file/load',    'pattern'=>'api/file',         'verb'=>'POST'),
                array('file/delete',  'pattern'=>'api/file',         'verb'=>'DELETE'),

                array('comment/new',  'pattern'=>'api/message/new',  'verb'=>'POST'),
                array('comment/new',  'pattern'=>'api/comment/new',  'verb'=>'POST'),
                array('answer/new',   'pattern'=>'api/answer/new',   'verb'=>'POST'),
			),
		),
		'db'=>array(
			'connectionString' => 'pgsql:host=localhost;port=5432;dbname=petfinder',
			'emulatePrepare' => true,
			'username' => 'whirpl',
			'password' => 'cegthgegth',
			'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	'params'=>array(
        'zoom' => [
            'max' => [
                'lng' => 0.2,
                'lat' => 0.4,
            ]
        ],
        'images' => [
            'path' => [
                'tmp'  => dirname(__FILE__).DIRECTORY_SEPARATOR.'../../images/temp/',
                'size' => [
                    'original' => [
                        'absolute' => dirname(__FILE__).DIRECTORY_SEPARATOR.'../../images/pets/original/',
                        'relative' => 'images/pets/original/'
                    ],
                    'small'    => [
                        'absolute' => dirname(__FILE__).DIRECTORY_SEPARATOR.'../../images/pets/small/',
                        'relative' => 'images/pets/small/'
                    ],
                ]
            ],


        ],

	),
);