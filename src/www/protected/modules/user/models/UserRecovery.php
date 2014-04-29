<?php

/**
 * This is the model class for table "user_recovery".
 *
 * The followings are the available columns in table 'user_recovery':
 * @property string $id
 * @property string $user_id
 * @property string $key
 * @property string $expired
 */
class UserRecovery extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserRecovery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_recovery';
	}
	
	public function scopes()
    {
        return array(
            'notsafe'=>array(
            	'select' => 'id',
            ),
        );
    }

	public function setActivationKey($user){
		$key = md5($user->username.microtime());
		$expired = date("Y-m-d H:i:s",time()+3600*24);
		$command = Yii::app()->db->createCommand();
		$command->delete($this->tableName(), 'user_id=:id', array(
			":id"=>$user->id,
		));
		$command->insert($this->tableName(), array(
   			'user_id'=>$user->id,
    		'key'=>$key,
    		'expired'=>$expired,
		));
		return $key;
	}
	
	public function getActivationKey(){
		$command = Yii::app()->db->createCommand();
		$command->delete($this->tableName(), 'expired > :expired', array(
			":expired"=>date("Y-m-d H:i:s"),
		));
	}
	
	public function clearUserRecords($id){
		$command = Yii::app()->db->createCommand();
		$command->delete(self::tableName(), 'user_id=:id', array(
			":id"=>$id,
		));
	}
	
	public function changePassword($user, $model){
		if($model->validate()) {
			$password = User::getHash($model->password);
			$user->updateAll(array('password' => $password, 'updated'=>date("Y-m-d H:i:s")), 'id='.$user->id);
		} else {
			return false;
		}
		return true;
	}
}