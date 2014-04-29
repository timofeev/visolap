<?php $this->breadcrumbs = array(
	'Управление доступом'=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments')=>array('assignment/view'),
	$model->getName(),
); ?>

<div id="userAssignments">

	<h2><?php echo Rights::t('core', 'Assignments for :username', array(
		':username'=>$model->getName()
	)); ?></h2>
	<div class="row">	
		<div class="assignments span8">

			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'dataProvider'=>$dataProvider,
				'template'=>'{items}',
				'hideHeader'=>true,
				'emptyText'=>Rights::t('core', 'This user has not been assigned any items.'),
				'type' => 'striped bordered condensed',
				'columns'=>array(
    				array(
    					'name'=>'name',
    					'header'=>Rights::t('core', 'Name'),
    					'type'=>'raw',
    					'htmlOptions'=>array('class'=>'name-column'),
    					'value'=>'$data->getNameText()',
    				),
    				array(
    					'name'=>'type',
    					'header'=>Rights::t('core', 'Type'),
    					'type'=>'raw',
    					'htmlOptions'=>array('class'=>'type-column'),
    					'value'=>'$data->getTypeText()',
    				),
    				array(
    					'header'=>'&nbsp;',
    					'type'=>'raw',
    					'htmlOptions'=>array('class'=>'actions-column'),
    					'value'=>'$data->getRevokeAssignmentLink()',
    				),
				)
			)); ?>

		</div>

		<div class="add-assignment span3 pull-right">

			<h4><?php echo Rights::t('core', 'Assign item'); ?></h4>

			<?php if( $formModel!==null ): ?>

				<div class="form">

					<?php $this->renderPartial('_form', array(
						'model'=>$formModel,
						'itemnameSelectOptions'=>$assignSelectOptions,
					)); ?>

				</div>

			<?php else: ?>

				<p class="info"><?php echo Rights::t('core', 'No assignments available to be assigned to this user.'); ?>

			<?php endif; ?>

		</div>
	</div>
</div>
