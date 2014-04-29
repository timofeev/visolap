<?php

class InstallModule extends CWebModule
{
	public $enable, $defaultDeveloperName, $defaultDeveloperEmail, $defaultDeveloperPassword,
					$defaultAdminName, $defaultAdminEmail, $defaultAdminPassword,
					$defaultUserName, $defaultUserEmail, $defaultUserPassword;
	
	public function init() {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'install.models.*',
			'install.components.*',
		));

		Yii::app()->theme = 'install';
		Yii::app()->getComponent('bootstrap');
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
