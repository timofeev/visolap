<?php
/**
 * Install class.
 */
 
class Install extends CModel {
	
	private static $_models=array();
	
	const	ROLE_DEVELOPER = "developer",
			ROLE_ADMIN = "admin",
			ROLE_USER = "user",
			ROLE_GUEST = "guest";
	
	public static function model($className=__CLASS__) {
	    if(isset(self::$_models[$className]))
	        return self::$_models[$className];
	    else {
	        $model=self::$_models[$className]=new $className(null);
	        return $model;
	    }
	}
	
	public function attributeNames(){
		return array();
	}
	
	public function setDefaultPriveleges(){
		$auth = Yii::app()->authManager;

		$auth->clearAll();
		
		//add role
		$auth->createAuthItem(self::ROLE_DEVELOPER, 2, 'Разработчик');
		$auth->createAuthItem(self::ROLE_ADMIN, 2, 'Администратор');
		$auth->createAuthItem(self::ROLE_USER, 2, 'Авторизованный пользователь');
		$auth->createAuthItem(self::ROLE_GUEST, 2, 'Гость');
		
		//add base task
		$auth->createAuthItem('manageUser', 1, 'Управление пользователями');
		$auth->createAuthItem('manageUserAccess', 1, 'Управление доступом пользователей');
		$auth->createAuthItem('manageAccess', 1, 'Управление доступом');
		
		//add operations for manage user
		$auth->createAuthItem('createUser', 0, 'Создание пользователя');
    	$auth->createAuthItem('listUser', 0, 'Просмотр списка пользователей');
    	$auth->createAuthItem('viewUser', 0, 'Просмотр данных пользователя');
    	$auth->createAuthItem('updateUser', 0, 'Изменение данных пользователя');
    	//удаление пользователя и запрет на удаление собственной учетной записи
    	$bizRule='return Yii::app()->user->id != $params["user"]->id;';
    	$auth->createAuthItem('deleteUser', 0, 'Удаление пользователя', $bizRule);
    	
    	$auth->createAuthItem('changeRole', 0, 'Изменение роли пользователя');
    	
    	//наследуем значения
    	$auth->addItemChild('manageAccess', 'changeRole');
    	
    	$auth->addItemChild('manageUser','createUser');
    	$auth->addItemChild('manageUser','listUser');
    	$auth->addItemChild('manageUser','viewUser');
    	$auth->addItemChild('manageUser','updateUser');
    	$auth->addItemChild('manageUser','deleteUser');
    	
    	$auth->addItemChild(self::ROLE_ADMIN, self::ROLE_USER);
    	$auth->addItemChild(self::ROLE_ADMIN, 'manageUser');
    	
    	$auth->addItemChild(self::ROLE_DEVELOPER, self::ROLE_ADMIN);
    	$auth->addItemChild(self::ROLE_DEVELOPER,'manageUserAccess');
    	$auth->addItemChild(self::ROLE_DEVELOPER,'manageAccess');
    	
	}
	
	public function createTables(){
		$auth = Yii::app()->authManager;
		$conn = Yii::app()->db;
		$transaction=$conn->beginTransaction();
		try {
			/* add metatags */
			$sql = 'DROP TABLE IF EXISTS `metatag`';
			$conn->createCommand($sql)->execute();
			$sql = 'CREATE TABLE IF NOT EXISTS `metatag` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) NOT NULL,
					  `description` varchar(255) NOT NULL,
					  `keywords` varchar(255) NOT NULL,
					  `path` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
    		$conn->createCommand($sql)->execute();


			$sql = 'DROP TABLE IF EXISTS `user`';
			$conn->createCommand($sql)->execute();
			$sql = 'CREATE TABLE `user` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `username` varchar(255) NOT NULL,
					  `password` varchar(255) NOT NULL,
					  `email` varchar(255) NOT NULL,
					  `created` datetime NOT NULL,
					  `updated` datetime NOT NULL,
					  `last_login` datetime NOT NULL,
					  `role` varchar(64) NOT NULL,
					  `status` tinyint(1) NOT NULL DEFAULT 1,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB';
    		$conn->createCommand($sql)->execute();
    		
    		$sql = 'DROP TABLE IF EXISTS `user_recovery`';
			$conn->createCommand($sql)->execute();
			$sql = 'CREATE TABLE `user_recovery` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) NOT NULL,
					  `key` varchar(255) NOT NULL,
					  `expired` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8';
    		$conn->createCommand($sql)->execute();
    		

    		$sql = 'DROP TABLE IF EXISTS `'.$auth->assignmentTable.'`';
			$conn->createCommand($sql)->execute();
			
			$sql = 'DROP TABLE IF EXISTS `'.$auth->itemChildTable.'`';
			$conn->createCommand($sql)->execute();
			
			$sql = 'DROP TABLE IF EXISTS `'.$auth->rightsTable.'`';
			$conn->createCommand($sql)->execute();
			
			$sql = 'DROP TABLE IF EXISTS `'.$auth->itemTable.'`';
			$conn->createCommand($sql)->execute();
			
			$sql = 'CREATE TABLE IF NOT EXISTS `'.$auth->rightsTable.'` (
					  `itemname` varchar(64) NOT NULL,
					  `type` int(11) NOT NULL,
					  `weight` int(11) NOT NULL,
					  PRIMARY KEY (`itemname`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
			$conn->createCommand($sql)->execute();
			
			$sql = 'CREATE TABLE `'.$auth->assignmentTable.'` (
					  `itemname` varchar(64) NOT NULL,
					  `userid` varchar(64) NOT NULL,
					  `bizrule` text,
					  `data` text,
					  PRIMARY KEY (`itemname`,`userid`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    		$conn->createCommand($sql)->execute();
    		
			$sql = 'CREATE TABLE `'.$auth->itemTable.'` (
					  `name` varchar(64) NOT NULL,
					  `type` int(11) NOT NULL,
					  `description` text,
					  `bizrule` text,
					  `data` text,
					  PRIMARY KEY (`name`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    		$conn->createCommand($sql)->execute();
    		
			$sql = 'CREATE TABLE `'.$auth->itemChildTable.'` (
					  `parent` varchar(64) NOT NULL,
					  `child` varchar(64) NOT NULL,
					  PRIMARY KEY (`parent`,`child`),
					  KEY `child` (`child`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    		$conn->createCommand($sql)->execute();
    		
    		$sql='ALTER TABLE `'.$auth->assignmentTable.'`
					ADD CONSTRAINT `'.$auth->assignmentTable.'_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `'.$auth->itemTable.'` (`name`) ON DELETE CASCADE ON UPDATE CASCADE';
    		$conn->createCommand($sql)->execute();
    		
    		$sql='ALTER TABLE `'.$auth->itemChildTable.'`
					ADD CONSTRAINT `'.$auth->itemChildTable.'_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `'.$auth->itemTable.'` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
					ADD CONSTRAINT `'.$auth->itemChildTable.'_ibfk_2` FOREIGN KEY (`child`) REFERENCES `'.$auth->itemTable.'` (`name`) ON DELETE CASCADE ON UPDATE CASCADE';
    		$conn->createCommand($sql)->execute();
    		
    		$sql='ALTER TABLE `'.$auth->rightsTable.'`
  					ADD CONSTRAINT `'.$auth->rightsTable.'_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `'.$auth->itemTable.'` (`name`) ON DELETE CASCADE ON UPDATE CASCADE';
    		$conn->createCommand($sql)->execute();
    		                         
    		
    		$transaction->commit();
		} catch(Exception $e) {
			$transaction->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
	
	public function addDefaultUsers(){
		$conn = Yii::app()->db;
		$auth = Yii::app()->authManager;
		$creation_date = date("Y-m-d H:i:s");
		
		// add developer
		$username = Yii::app()->getModule('install')->defaultDeveloperName;
		$email = Yii::app()->getModule('install')->defaultDeveloperEmail;
		$password = User::getHash(Yii::app()->getModule('install')->defaultDeveloperPassword);

		$sql = 'INSERT INTO `user` (`username`, `email`, `password`, `created`)
					VALUES("'.$username.'","'.$email.'","'.$password.'","'.$creation_date.'")';
		$conn->createCommand($sql)->execute();
		$auth->assign(self::ROLE_DEVELOPER, $conn->getLastInsertID());
		
		// add admin
		$username = Yii::app()->getModule('install')->defaultAdminName;
		$email = Yii::app()->getModule('install')->defaultAdminEmail;
		$password = User::getHash(Yii::app()->getModule('install')->defaultAdminPassword);

		$sql = 'INSERT INTO `user` (`username`, `email`, `password`, `created`)
					VALUES("'.$username.'","'.$email.'","'.$password.'","'.$creation_date.'")';
		$conn->createCommand($sql)->execute();
		$auth->assign(self::ROLE_ADMIN, $conn->getLastInsertID());
		
		//add user
		// add developer
		$username = Yii::app()->getModule('install')->defaultUserName;
		$email = Yii::app()->getModule('install')->defaultUserEmail;
		$password = User::getHash(Yii::app()->getModule('install')->defaultUserPassword);

		$sql = 'INSERT INTO `user` (`username`, `email`, `password`, `created`)
					VALUES("'.$username.'","'.$email.'","'.$password.'","'.$creation_date.'")';
		$conn->createCommand($sql)->execute();
		$auth->assign(self::ROLE_USER, $conn->getLastInsertID());
	}
}

?>