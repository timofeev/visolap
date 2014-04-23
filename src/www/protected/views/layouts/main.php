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
    <?php Yii::app()->clientScript->registerCssFile('/js/lib/jquery.contextMenu/jquery.contextMenu.css'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/lib/jquery.contextMenu/jquery.ui.position.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/lib/jquery.contextMenu/jquery.contextMenu.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/jquery.form.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/lib/jquery.json.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/lib/jquery.htmlize.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/lib/jquery.serialize-object.min.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/d3.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/crossfilter.js'); ?>    
    <?php Yii::app()->clientScript->registerScriptFile('/js/dc.js'); ?>    
    <?php Yii::app()->clientScript->registerScriptFile('/js/core.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/graph.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/layout.js'); ?>

	<title>Visual OLAP</title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>
<body>
	<?php echo $content; ?>
</body>
</html>