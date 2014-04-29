<!doctype html>
<html>
<head>
	<meta charset="utf-8">	
	<?php	
	    $cs = Yii::app()->clientScript;
	    $cs->registerCoreScript('jquery');
    	$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/script.js');
    	$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');
	?>
	<title><?php echo $this->pageTitle; ?></title>	
</head>
<body>
<div id="wrapper">
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'collapse' => true,
	'brand'=>Yii::app()->name,
	'brandUrl'=>Yii::app()->homeUrl,
	'htmlOptions' => array(
		'class' => 'navbar-inverse',
	),
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'items'=>array(
				
				array(
					'label'=>'Управление доступом',
					'url'=>array('#'),
					'active'=>isset(Yii::app()->controller->module->id) && Yii::app()->controller->module->id == 'rights',
					'visible'=>Yii::app()->user->checkAccess('manageAccess'),
					'items'=>array(
						array(
							'label'=>Rights::t('core', 'Assignments'),
							'url'=>array('/rights/assignment/view'),
							'itemOptions'=>array('class'=>'item-assignments'),
						),
						'---',
						array(
							'label'=>Rights::t('core', 'Permissions'),
							'url'=>array('/rights/authItem/permissions'),
							'itemOptions'=>array('class'=>'item-permissions'),
						),
						array(
							'label'=>Rights::t('core', 'Roles'),
							'url'=>array('/rights/authItem/roles'),
							'itemOptions'=>array('class'=>'item-roles'),
						),
						array(
							'label'=>Rights::t('core', 'Tasks'),
							'url'=>array('/rights/authItem/tasks'),
							'itemOptions'=>array('class'=>'item-tasks'),
						),
						array(
							'label'=>Rights::t('core', 'Operations'),
							'url'=>array('/rights/authItem/operations'),
							'itemOptions'=>array('class'=>'item-operations'),
						),
					),
				),
			),
		),
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items'=>array(
				array(	'label'=>'Install', 
						'url'=>array('/install'), 
						'visible'=>Yii::app()->user->checkAccess('developer') && Yii::app()->getModule('install')->enable, 
						'active'=>Yii::app()->controller->module && Yii::app()->controller->module->id == 'install'
					 ),
				array('label'=>'Авторизация', 'url'=>array('/auth'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/logout'), 'visible'=>!Yii::app()->user->isGuest,),
			),
		),
	),
)); 

?>
	<div class="container" id="center">		
		<div class="sections-menu">
			<?php
			$this->widget('bootstrap.widgets.TbMenu', array(
			    'type'=>'list',
			    'htmlOptions' => array('class' => 'well'),
			    'items'=>array(
			        // How to use with icons: http://www.cniska.net/yii-bootstrap/#tbMenu
	                array(
						'label'=>'Пользователи',
						'url'=>array('/user/user/index'),
						'active'=>Yii::app()->controller->id == 'user',
						'visible'=>Yii::app()->user->checkAccess('manageUser')
					),	                
			    ),
			)); ?>
		</div>
		<div id="content">		

			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
					'homeLink' => CHtml::link('Главная', '/admin/'),
					'separator' => ' &rsaquo; '
				)); ?>
			<?php endif?>			
			<?php echo $content; ?>
		</div>		
	</div>
</div><!-- #wrapper -->
</body>
</html>