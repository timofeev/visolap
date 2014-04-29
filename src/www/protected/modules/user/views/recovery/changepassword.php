<?php
if (isset($error)) {
?>
     <div id="recovery_error">
     	<div class="alert alert-error"><?php echo $error ?></div>
	     <div class="additional_link">
			<div><a href="/" title="Вернуться на сайт">Вернуться на сайт</a></div>
		</div>
	 </div>
<?php
} else if (Yii::app()->user->hasFlash('changeSuccess')) {
?>
	<div id="login">
		<div class="alert alert-success">
	        <?php  echo Yii::app()->user->getFlash('changeSuccess'); ?>
	    </div>
	    <div class="additional_link">
			<div class="float_l">
				<a href="/auth/" title="Авторизация">Авторизация</a>
			</div>
			<div class="float_r"><a href="/" title="Вернуться на сайт">Вернуться на сайт</a></div>
		</div>
	</div>
<?php	
} else {
?>
	<div id="login">
		<h1><?php echo User::t("Изменение пароля"); ?></h1>

		<div class="well">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'change-password-form',
				'enableClientValidation'=>true,
				'errorMessageCssClass'=>'alert alert-error',
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'action' => $this->createUrl('/recovery/changepassword'),
			)); ?>
			<fieldset>
				<div class="control-group">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password', array('class'=>'width_340')); ?>
				<?php echo $form->error($model,'password'); ?>
				</div>
				<div class="control-group">
				<?php echo $form->labelEx($model,'password_retype'); ?>
				<?php echo $form->passwordField($model,'password_retype', array('class'=>'width_340')); ?>
				<?php echo $form->error($model,'password_retype'); ?>
				</div>
				<div class="form-actions">
					<div class="float_r">
						<?php echo CHtml::submitButton('Изменить пароль', array('class'=>'btn btn-primary')); ?>
					</div>
				</div>
			</fieldset>
		</div><!-- well -->
		<div class="additional_link">
			<div class="float_l">
				<a href="/auth/" title="Авторизация">Авторизация</a>
			</div>
			<div class="float_r"><a href="/" title="Вернуться на сайт">Вернуться на сайт</a></div>
		</div>
				<?php echo $form->errorSummary($model,NULL,NULL,$htmlOptions=array('class'=>'alert alert-error')); ?>
			<?php $this->endWidget(); ?>
	</div><!-- #login -->
<?php
}
?>