<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Visual OLAP',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.rights.*',
		'application.modules.rights.components.*',
		'application.modules.user.*',
		'application.modules.install.models.*',
		'application.modules.user.models.*'		
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'wiki' => array(
			'userAdapter' => array(
				'class' => 'WikiUser',
			),
		),
		'user' => array(
			'recordsPerPage' => 20,
		),
		'rights'=>array(
			'superuserName'=>'developer', // Name of the role with super user privileges.
			'authenticatedName'=>'user', // Name of the authenticated user role.
			'userIdColumn'=>'id', // Name of the user id column in the database.
			'userNameColumn'=>'username', // Name of the user name column in the database.
			'enableBizRule'=>true, // Whether to enable authorization item business rules.
			'enableBizRuleData'=>false, // Whether to enable data for business rules.
			'displayDescription'=>true, // Whether to use item description instead of name.
			'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages.
			'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages.
			'install'=>true, // Whether to install rights.
			'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested.
			'layout'=>'rights.views.layouts.main', // Layout to use for displaying Rights.
			'appLayout'=>'webroot.themes.admin.views.layouts.main', // Application layout.
			'cssFile'=>false, // Style sheet file to use for Rights.
			'install'=>false, // Whether to enable installer.
			'debug'=>true,
		),
       	'install' => array(
       		'enable' => true,
       		
       		'defaultDeveloperName' => 'adminz',
       		'defaultDeveloperEmail' => 'dev@example.com',
       		'defaultDeveloperPassword' => 'adminz',
       		
       		'defaultAdminName' => 'admin',
       		'defaultAdminEmail' => 'admin@example.com',
       		'defaultAdminPassword' => 'admin',
       		
       		'defaultUserName' => 'user',
       		'defaultUserEmail' => 'user@example.com',
       		'defaultUserPassword' => 'user',
       	),
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>'/auth',
			'class'=>'RWebUser',
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'auth'=>'user/user/login',
				'user'=>'user/user/index',
				'recovery'=>'user/recovery/recovery',
				'logout'=>'user/user/logout',
				'install/<action>' => 'install/default/<action>',
				'user/<action:\w+>'=>'user/user/<action>',
				'recovery/<action:\w+>'=>'user/recovery/<action>',
				'/'=>'wiki/layout/index',
				'page/<uid>' => 'wiki/layout/load',
				'save/*' => 'wiki/layout/save',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',				
				
				// add support for modules
				'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
			),
		),
		
		'db'=>array(
			//'connectionString'=>'mysql:host=localhost;dbname=visolap',
			'connectionString'=>'mysql:host=mysql.hostinger.ru;dbname=u968316744_volap',
			'emulatePrepare'=>true,
			//'username'=>'visolap',
			'username'=>'u968316744_volap',
			'password'=>'visolap',			
			'charset'=>'utf8',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
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
		'cache' => array(
	       'class' => 'CFileCache',
	    ),
	    'authManager'=>array(
            //'class'=>'CDbAuthManager',
            'class'=>'RDbAuthManager',
            'connectionID'=>'db',
            'defaultRoles'=>array('guest'),
            'itemTable' => 'user_auth_item',
        	'itemChildTable' => 'user_auth_item_child',
        	'assignmentTable' => 'user_auth_assignment',
        	'rightsTable' => 'user_auth_right',
        ),
		
		'session'=>array(
			'class' => 'CDbHttpSession',
			'connectionID' => 'db',
            'sessionTableName' => 'session',
		),
		'hasher'=>array (
			'class'=>'ext.phpass.Phpass',
			'hashPortable'=>false,
			'hashCostLog2'=>12,
		),
		'swiftMailer' => array(
		    'class' => 'ext.swiftMailer.SwiftMailer',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);