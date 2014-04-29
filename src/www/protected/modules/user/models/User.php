<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $created
 * @property string $updated
 * @property string $last_login
 * @property integer $status
 */
class User extends CActiveRecord
{
	const DEFAULT_ROLE = "user";
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function scopes()
    {
        return array(
            'notsafe'=>array(
            	'select' => 'id, username, email, status, created',
            ),
        );
    }
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email', 'required', 'on'=>'update'),
			array('username, password, email', 'required', 'on'=>'create'),
			array('username, email', 'unique'),
			array('email','email'),
			array('username', 'length', 'max'=>128, 'min' => 3,'message' => User::t("Неправильное имя пользователя<br/>(минимум 3 символа).")),
			array('password', 'length', 'max'=>128, 'min' => 8,'message' => User::t("Неправильный пароль<br/>(минимум 8 символов)."), 'on' => 'create'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, created, last_login, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Имя&nbsp;пользователя',
			'password' => 'Пароль',
			'email' => 'E-Mail',
			'status' => 'Статус',
			'created' => 'Дата создания',
			'updated' => 'Дата изменения',
			'last_login' => 'Последний вход',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id, true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('status',$this->status,true);
		
		$dataProvider = new CActiveDataProvider($this, array(
			'pagination'=>array(
            	'pageSize'=>Yii::app()->controller->module->recordsPerPage,
        	),
			'criteria'=>$criteria,
		));
		return $dataProvider;
	}
   
 	public static function getHash($password){
		return Yii::app()->hasher->hashPassword($password);
	}
	
	public static function t($str='',$params=array(),$dic='user') {
		return Yii::t("User.".$dic, $str, $params);
	}
	
	public static function searchById($user_id){
		if ($user_id){
			$user = User::model()->notsafe()->find('id='.$user_id);
			return $user;
		}
		return false;
	}
	
	public function beforeSave() {
	    parent::beforeSave();
	    /*
	     * Если пользователь не имеет права изменять роль, то мы должны
	     * установить роль по-умолчанию (user)
	     */
	    if ($this->isNewRecord) {
			$this->created = date("Y-m-d H:i:s");
			$this->password = self::getHash($this->password);
			if (!Yii::app()->user->checkAccess('changeRole')) {
				$this->role = User::DEFAULT_ROLE;
			}
	    }
	    return true;
	}
	
	public function afterSave() {
	    parent::afterSave();
	    //связываем нового пользователя с ролью
		$auth=Yii::app()->authManager;
	    if (isset($this->prevRole)){
		    //предварительно удаляем старую связь
		    $auth->revoke($this->prevRole, $this->id);
		    $auth->assign($this->role, $this->id);
	    	$auth->save();
		}
   		return true;
	}
	
	public function beforeDelete() {
	    parent::beforeDelete();
	    //убираем связь удаленного пользователя с ролью
	    $auth=Yii::app()->authManager;
	    $auth->revoke($this->role, $this->id);
	    $auth->save();
	    return true;
	}
}