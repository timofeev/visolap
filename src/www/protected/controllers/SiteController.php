<?php

class SiteController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionSubmit($source) {
		$db = Yii::app()->db;
		$command = $db->createCommand($source);
		try {
			$list = $command->queryAll();
			echo CJSON::encode($list);			
		} catch (Exception $e) {
			echo CJSON::encode(array('error' => $e->errorInfo[2]));
		}		
	}
	
	public function actionForm($type) {		
		$this->renderPartial($type);
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}