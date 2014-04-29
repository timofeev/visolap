<div class="form">

<?php $form=$this->beginWidget('CActiveForm',array(
			'enableClientValidation'=>true,
		)); ?>
	<div class="control-group">
		<?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>
		<?php echo $form->error($model, 'itemname', array('class'=>"alert alert-error")); ?>
	</div>
	
	<div class="buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'icon'=>'ok', 'type' => 'success', 'label'=>Rights::t('core', 'Add'))); ?>
	</div>

<?php $this->endWidget(); ?>
</div>