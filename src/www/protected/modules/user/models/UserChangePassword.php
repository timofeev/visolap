<?php
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserChangePassword extends CFormModel {
	public $password;
	public $password_retype;
	
	public function rules() {
		return array(
			array('password, password_retype', 'required'),
			array('password', 'length', 'max'=>128, 'min' => 8,'message' => User::t("Неправильный пароль<br/>(минимум 8 символов).")),
			array('password_retype', 'compare', 'compareAttribute'=>'password', 'message' => User::t("Повторный пароль не совпадает с введенным.")),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'password'=>User::t("Новый пароль"),
			'password_retype'=>User::t("Повторите пароль"),
		);
	}
} 