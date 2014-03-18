<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="/css/dc.css" />
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/lib/jquery.ui/js/jquery-ui-1.10.4.custom.min.js'); ?>
    <?php Yii::app()->clientScript->registerCssFile('/js/lib/jquery.ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/jquery.form.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/d3.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/crossfilter.js'); ?>    
    <?php Yii::app()->clientScript->registerScriptFile('/js/dc.js'); ?>    
    <?php Yii::app()->clientScript->registerScriptFile('/js/core.js'); ?>

	<title>Visual OLAP</title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>
<body>
	<?php $this->widget('bootstrap.widgets.TbNavbar',array(
	    'items'=>array(
	        array(
	            'class'=>'bootstrap.widgets.TbMenu',
	            'items'=>array(
	                array('label'=>'Главная', 'url'=>'/'),
	                array(
	                	'label'=>'Добавить график',	                	
	                	'itemOptions' => array('id' => 'addButton', 'style' => 'display: none;'),
	                	'items' => array(
	                		array('label'=>'Линейный', 'url'=>'javascript:void(0);', 'itemOptions' => array('data-type' => 'linear')),
	                	)
	                ),
	            ),
	        ),
	    ),
	)); ?>
<div class="container" id="page">
	<?php echo $content; ?>
</div><!-- page -->
</body>
</html>