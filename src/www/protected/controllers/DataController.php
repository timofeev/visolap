<?php
class DataController extends WindowController {
	public function actionForm() {		
		$this->renderWindow('form', array(), 'data-window');
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
}
?>