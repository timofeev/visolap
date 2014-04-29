<?php

class UserRecoveryForm extends CFormModel
{
	public $login_or_email, $verifyCode, $user_id, $user;
	
	public function rules()
	{
		return array(
			// username and password are required
			array('login_or_email, verifyCode', 'required'),
			array(	'login_or_email', 'match',
					'pattern' => '/^[-_A-Za-z0-9@.\s,]+$/u',
					'message' => 'Неверные символы.'),
			array( 'verifyCode', 'captcha'),
			array('login_or_email', 'checkexists'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'login_or_email'=>'Имя пользователя или email',
			'verifyCode' => 'Код проверки',
		);
	}
	
	public function checkexists($attribute,$params) {
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			//var_dump(User::model());
			if (strpos($this->login_or_email,"@")) {
				$this->user=User::model()->findByAttributes(array('email'=>$this->login_or_email));
				if ($this->user)
					$this->user_id=$this->user->id;
			} else {
				$this->user=User::model()->findByAttributes(array('username'=>$this->login_or_email));
				if ($this->user)
					$this->user_id=$this->user->id;
			}
			
			if($this->user===null)
				if (strpos($this->login_or_email,"@")) {
					$this->addError("login_or_email",Yii::t("Yii", "Неверный email."));
				} else {
					$this->addError("login_or_email",Yii::t("Yii", "Неверное имя пользователя."));
				}
		}
	}
}
