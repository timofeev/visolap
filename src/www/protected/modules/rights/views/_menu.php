<?php
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'tabs',
	'stacked' => false,
	'items'=>array(
		array(
			'label'=>Rights::t('core', 'Assignments'),
			'url'=>array('assignment/view'),
			'itemOptions'=>array('class'=>'item-assignments',),
			'active'=>Yii::app()->request->url == '/rights/assignment/view' || Yii::app()->request->url == '/rights',
		),
		array(
			'label'=>Rights::t('core', 'Permissions'),
			'url'=>array('authItem/permissions'),
			'itemOptions'=>array('class'=>'item-permissions'),
			'active'=>Yii::app()->request->url == '/rights/authItem/permissions',
		),
		array(
			'label'=>Rights::t('core', 'Roles'),
			'url'=>array('authItem/roles'),
			'itemOptions'=>array('class'=>'item-roles'),
			'active'=>Yii::app()->request->url == '/rights/authItem/roles',
		),
		array(
			'label'=>Rights::t('core', 'Tasks'),
			'url'=>array('authItem/tasks'),
			'itemOptions'=>array('class'=>'item-tasks'),
			'active'=>Yii::app()->request->url == '/rights/authItem/tasks',
		),
		array(
			'label'=>Rights::t('core', 'Operations'),
			'url'=>array('authItem/operations'),
			'itemOptions'=>array('class'=>'item-operations'),
			'active'=>Yii::app()->request->url == '/rights/authItem/operations',
		),
	)
));	?>