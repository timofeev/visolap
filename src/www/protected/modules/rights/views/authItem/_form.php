<div class="form span-12 well">

<?php if( $model->scenario==='update' ): ?>

	<h3><?php echo Rights::getAuthItemTypeName($model->type); ?></h3>

<?php endif; ?>
	
<?php $form=$this->beginWidget('CActiveForm', array(
		'enableClientValidation'=>true,
)); ?>

	<div class="control-group">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('maxlength'=>255, 'class'=>'text-field')); ?>
		<?php echo $form->error($model, 'name', array('class'=>"alert alert-error")); ?>
		<p class="hint"><?php echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></p>
	</div>

	<div class="control-group">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'text-field')); ?>
		<?php echo $form->error($model, 'description', array('class'=>"alert alert-error")); ?>
		<p class="hint"><?php echo Rights::t('core', 'A descriptive name for this item.'); ?></p>
	</div>

	<?php if( Rights::module()->enableBizRule===true ): ?>

		<div class="control-group">
			<?php echo $form->labelEx($model, 'bizRule'); ?>
			<?php echo $form->textField($model, 'bizRule', array('maxlength'=>255, 'class'=>'text-field')); ?>
			<?php echo $form->error($model, 'bizRule', array('class'=>"alert alert-error")); ?>
			<p class="hint"><?php echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></p>
		</div>

	<?php endif; ?>

	<?php if( Rights::module()->enableBizRule===true && Rights::module()->enableBizRuleData ): ?>

		<div class="control-group">
			<?php echo $form->labelEx($model, 'data'); ?>
			<?php echo $form->textField($model, 'data', array('maxlength'=>255, 'class'=>'text-field')); ?>
			<?php echo $form->error($model, 'data', array('class'=>"alert alert-error")); ?>
			<p class="hint"><?php echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></p>
		</div>

	<?php endif; ?>

	<div class="buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'icon'=>'ok', 'type' => 'success', 'label'=>Rights::t('core', 'Save'))); ?>
		&nbsp;
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'link', 'icon'=>'remove', 'label'=>Rights::t('core', 'Cancel'), 'url' => Yii::app()->user->rightsReturnUrl)); ?>
	</div>

<?php $this->endWidget(); ?>

</div>