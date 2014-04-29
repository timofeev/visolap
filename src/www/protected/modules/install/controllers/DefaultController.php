<?php

class DefaultController extends Controller {


	public function actionIndex() {
		if (!Yii::app()->controller->module->enable)
			throw new CHttpException(404, 'The requested page does not exist.');
		$this->render('index');
	}
	
	public function actionStart() {
		if (!Yii::app()->controller->module->enable)
			throw new CHttpException(404, 'The requested page does not exist.');
		$model = $this->loadModel();
				
        $model->createTables();
		$model->setDefaultPriveleges();
		$model->addDefaultUsers();
		
		$this->render('start');
	}
	
	public function loadModel() {
		Yii::import('install.models.Install');
		$model=Install::model();
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}