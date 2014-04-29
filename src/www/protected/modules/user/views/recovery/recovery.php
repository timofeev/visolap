<div id="login">
	<h1><?php echo Yii::app()->name;?></h1>
    
	<div class="well">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'password-recovery-form',
			'enableClientValidation'=>true,
			'errorMessageCssClass'=>'alert alert-error',
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>
		
		<fieldset>
			<?php echo $form->labelEx($model,'login_or_email'); ?>
			<?php echo $form->textField($model,'login_or_email', array('class'=>'width_340')); ?>
			<?php echo $form->error($model,'login_or_email'); ?>
			
			<?if(extension_loaded('gd')):?>
    			<?php echo $form->labelEx($model, 'verifyCode')?>
    			<?php $this->widget('CCaptcha')?>
    			<?php echo $form->textField($model, 'verifyCode')?>
    			<?php echo $form->error($model,'verifyCode'); ?>
			<?endif?>
			
			<div class="form-actions">
				<div class="float_r">
					<?php echo CHtml::submitButton('Восстановить', array('class'=>'btn btn-primary')); ?>
				</div>
			</div>
		</fieldset>
	</div><!-- well -->
	<div class="additional_link" style="overflow: hidden;">
		<div class="float_l">
			<a href="/auth/" title="Авторизация">Авторизация</a>
		</div>
		<div class="float_r"><a href="/" title="Вернуться на сайт">Вернуться на сайт</a></div>
	</div>
	<?php echo $form->errorSummary($model,NULL,NULL,$htmlOptions=array('class'=>'alert alert-error')); ?>
		<?php $this->endWidget(); ?>
	<?php if(Yii::app()->user->hasFlash('recoveryMessage')):?>
    <div class="alert alert-info">
        <?php  echo Yii::app()->user->getFlash('recoveryMessage'); ?>
    </div>
    <?php
        Yii::app()->clientScript->registerScript(
            'myShowHideEffect',
            '$(".success").slideDown("slow", function(){$(".info").animate({opacity: 1.0}, 1000).fadeOut("slow");});',
            CClientScript::POS_READY
        );
    ?>
    <?php endif; ?>
</div><!-- #login -->