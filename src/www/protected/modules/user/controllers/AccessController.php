<?php

class AccessController extends Controller {
	


	public function actionIndex() {

		//проверяем, может ли пользователь изменять данную запись

		if (!Yii::app()->user->checkAccess('manageAccess')) {
	        throw new CHttpException(403,'Forbidden');
	    }
	    
	    $model = $this->loadModel();
		$auth = Yii::app()->authManager;
	    
	    echo "<br /><br /><br /><br /><pre>";
	    
	    foreach ($auth->getAuthItems(2) as $role){
			echo "<br />".$role->name."<br />";
			$childrens = $auth->getItemChildren($role->name);
			foreach ($childrens as $child){
				echo "<br />- - ".$child->type."- - - ".$child->description;
			}
			//print_r($auth->getItemChildren($role->name));
	    }
	    
		$this->render('index', array(
			'model' => $model,
		));
	}
	
	public function loadModel() {
		$model=User::model();
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	protected function setDefaultPriveleges() {
		$auth = Yii::app()->authManager;

		$auth->clearAll();
		
		//add role
		$auth->createAuthItem('root', 2, 'Суперпользователь');
		$auth->createAuthItem('admin', 2, 'Администратор');
		$auth->createAuthItem('user', 2, 'Авторизованный пользователь');
		$auth->createAuthItem('guest', 2, 'Гость');
		
		//add base task
		$auth->createAuthItem('manageUser', 1, 'Управление пользователями');
		$auth->createAuthItem('manageUserAccess', 1, 'Управление доступом пользователей');
		$auth->createAuthItem('manageRole', 1, 'Управление ролями');
		
		//add base operations for manage user
		$auth->createAuthItem('createUser', 0, 'Создание пользователя');
    	$auth->createAuthItem('viewUsers', 0, 'Просмотр списка пользователей');
    	$auth->createAuthItem('detailViewUser', 0, 'Просмотр данных пользователя');
    	$auth->createAuthItem('updateUser', 0, 'Изменение данных пользователя');
    	//удаление пользователя и запрет на удаление собственной учетной записи
    	$bizRule='return Yii::app()->user->id != $params["user"]->id;';
    	$auth->createAuthItem('deleteUser', 0, 'Удаление пользователя', $bizRule);
    	
    	$auth->createAuthItem('changeRole', 0, 'Изменение роли пользователя');
    	
    	//наследуем значения
    	$auth->addItemChild('manageRole', 'changeRole');
    	
    	$auth->addItemChild('manageUser','createUser');
    	$auth->addItemChild('manageUser','viewUsers');
    	$auth->addItemChild('manageUser','detailViewUser');
    	$auth->addItemChild('manageUser','updateUser');
    	$auth->addItemChild('manageUser','deleteUser');
    	
    	$auth->addItemChild('admin','user');
    	$auth->addItemChild('admin','manageUser');
    	
    	$auth->addItemChild('root','admin');
    	$auth->addItemChild('root','manageUserAccess');
    	$auth->addItemChild('root','manageRole');
    	
    	//TODO Сделать добавление пользователя
    	
    	$auth->assign('root', 1);
	}
}