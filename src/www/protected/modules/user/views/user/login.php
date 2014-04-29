<div id="login">
	<h1><?echo Yii::app()->name?></h1>
	<div class="well">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'login-form',
			'enableClientValidation'=>true,
			'errorMessageCssClass'=>'alert alert-error',
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>
	
		<fieldset>
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username', array('class'=>'width_340')); ?>
			<?php echo $form->error($model,'username'); ?>
	
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password', array('class'=>'width_340')); ?>
			<?php echo $form->error($model,'password'); ?>
			<br/>
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe', array('class'=>'label_checkbox')); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
	
			<div class="form-actions">
				<div class="float_r">
					<?php echo CHtml::submitButton('Вход', array('class'=>'btn btn-primary')); ?>
				</div>
			</div>
		</fieldset>

		<?php $this->endWidget(); ?>

	</div><!-- well -->
	<div class=" additional_link">
		<div class="float_l">
			<a href="/recovery/" title="Забыли пароль?">Забыли пароль?</a>
		</div>
		<div class="float_r">
			<a href="/" title="Вернуться на сайт">Вернуться на сайт</a>
		</div>
	</div>
			
</div><!-- #login -->