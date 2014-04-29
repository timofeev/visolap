<?php

class RecoveryController extends Controller {
	public $defaultAction = 'recovery';

    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array('captcha'),
                'users'=>array('*'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
 
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
            ),
		);
    }
	
	/**
	 * Recovery password
	 */
	public function actionRecovery () {

		if (Yii::app()->user->id){
		   	throw new CHttpException(404,'The requested page does not exist.');
		} else {
			$model = new UserRecoveryForm;
			if(isset($_POST['UserRecoveryForm'])) {
				$model->attributes=$_POST['UserRecoveryForm'];
				if($model->validate()) {
					$user = $model->user;
					$activkey = $this->loadModel()->setActivationKey($user);
					$activation_url = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('recovery/activation',array("key" => $activkey, "email" => $user->email));
					
					$this->recoveryLinkEmail($user->email, $activation_url);

					Yii::app()->user->setFlash('recoveryMessage',User::t("Инструкции для изменения пароля высланы на email."));
					$this->refresh();
				}
			}
			else {
				
			}
			$this->pageTitle = Yii::app()->name." - Восстановление пароля";
			$this->layout = 'blank';
			$this->render('recovery',array('model'=>$model));
		}
	}
	
	public function actionActivation($key, $email){
		if ($key && $email){
			$userRecovery = UserRecovery::model();
			
		    $command = Yii::app()->db->createCommand();

			$command->delete($userRecovery->tableName(), 'expired < :expired', array(
				":expired"=>date ("Y-m-d H:i:s"),
			));
			
			$findUser = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if ($findUser){
				if ($findUser->status){
					$findRecovery = UserRecovery::model()->notsafe()->findByAttributes(array('user_id'=>$findUser->id, 'key'=>$key));
					if ($findRecovery){
						$model = new UserChangePassword;
						Yii::app()->user->setState('recoveryUser', $findUser->id);
						$this->pageTitle = Yii::app()->name." - Восстановление пароля";
						$this->layout = 'blank';
						$this->render('changepassword',array('model'=>$model));
					} else {
						$error = 'Ошибка! Неправильный или устаревший ключ активации.';

						$this->pageTitle = Yii::app()->name." - Ошибка восстановления пароля.";
						$this->layout = 'blank';
						$this->render('changepassword',array('error'=>$error));
					}
				} else {
					$error = 'Ошибка! Пользователь заблокирован.<br/>Восстановление пароля невозможно.';
					$this->pageTitle = Yii::app()->name." - Ошибка восстановления пароля.";
					$this->layout = 'blank';
					$this->render('changepassword',array('error'=>$error));
				}
			} else {
				$error = 'Ошибка! Пользователь с таким e-mail не найден.';

				$this->pageTitle = Yii::app()->name." - Ошибка восстановления пароля.";
				$this->layout = 'blank';
				$this->render('changepassword',array('error'=>$error));
			}
		} else {
			throw new CHttpException(404,'The requested page does not exist.');
		}
	}
	
	public function actionChangepassword(){
		$model = new UserChangePassword;
		if (!Yii::app()->user->hasState('recoveryUser')){
			$error = 'Ошибка! Доступ на эту страницу закрыт.';

			$this->pageTitle = Yii::app()->name." - Ошибка. Доступ запрещен.";
			$this->layout = 'blank';
			$this->render('changepassword',array('error'=>$error));
		}
		
		if (isset($_POST["UserChangePassword"])){
			$model->attributes = $_POST["UserChangePassword"];
		} else {
			$error = 'Ошибка! Доступ на эту страницу закрыт.';

			$this->pageTitle = Yii::app()->name." - Ошибка. Доступ запрещен.";
			$this->layout = 'blank';
			$this->render('changepassword',array('error'=>$error));
		}
		
		$user = User::searchById(Yii::app()->user->getState('recoveryUser'));

		if ($user){
			$changed = $this->loadModel()->changePassword($user, $model);
			Yii::app()->user->clearStates();
			if (!$changed){
				$error = 'Ошибка! Некорректный пароль. Пароль не изменен.';

				$this->pageTitle = Yii::app()->name." - Ошибка. Пароль не изменен.";
				$this->layout = 'blank';
				$this->render('changepassword',array('error'=>$error));
			} else {
				$this->successEmail($user->email, $user->username, $model->password);
				$userRecovery = new UserRecovery;
				$userRecovery->clearUserRecords($user->id);
				Yii::app()->user->setFlash('changeSuccess', User::t('Пароль изменен, письмо с измененными данными выслано на email.'));
				$this->pageTitle = Yii::app()->name." - Пароль успешно изменен.";
				$this->layout = 'blank';
				$this->render('changepassword');
			}
		}
	}
	
	public function loadModel()
	{
		$model=UserRecovery::model();
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	protected function recoveryLinkEmail($email, $activation_url){
 	    $adminEmail = Yii::app()->params['adminEmail'];
 	    $this->layout = ('mail');
	    $content = $this->render('recovery_mail', array('activation_url'=>$activation_url), true);
	 
	    $sm = Yii::app()->swiftMailer;
	    $transport = $sm->mailTransport();
        $mailer = $sm->mailer($transport);
	 	$subject = "Восстановление пароля на сайте ".Yii::app()->name;
	    $message = $sm
	        ->newMessage($subject)
	        ->setFrom(array($adminEmail => 'Администрация сайта '.Yii::app()->name))
	        ->setTo(array($email => ''))
	        ->addPart($content, 'text/html');
	 
	    // Send mail
	    $result = $mailer->send($message);
	    //var_dump($result);
	}
	
	protected function successEmail($email, $username, $password){
 	    $adminEmail = Yii::app()->params['adminEmail'];
 	    $this->layout = ('mail');
	    $content = $this->render('success_mail', array('username'=>$username, 'password' => $password), true);
	 
	    $sm = Yii::app()->swiftMailer;
	    $transport = $sm->mailTransport();
	    $mailer = $sm->mailer($transport);
	 	$subject = "Изменение пароля на сайте ".Yii::app()->name;
	    $message = $sm
	        ->newMessage($subject)
	        ->setFrom(array($adminEmail => 'Администрация сайта '.Yii::app()->name))
	        ->setTo(array($email => ''))
	        ->addPart($content, 'text/html');
	    // Send mail
	    $result = $mailer->send($message);
	}
}