<?php

class UserIdentity extends CUserIdentity {
	
	private $_id;
		
	public function authenticate() {

		$record=User::model()->findByAttributes(array('username'=>$this->username));

		if($record === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else if(!Yii::app()->hasher->checkPassword($this->password, $record->password)){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} else if ($record->status != 1 ) {
			$this->errorCode = self::ERROR_USER_BLOCKED;
		} else {
			$this->_id = $record->id;
			$this->setState('username', $record->username);
			$record->last_login = date('Y-m-d H:i:s');
			$record->save();
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
 
	public function getId() {
		return $this->_id;
	}

}