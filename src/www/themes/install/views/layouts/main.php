<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/jquery-1.7.2.min.js"></script>

	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/script.js"></script>
	<?php
	// Suppress Yii's autoload of JQuery
	// We're loading it at bottom of page (best practice)
	// from Google's CDN with fallback to local version
	Yii::app()->clientScript->scriptMap=array(
		'jquery.js'=>true,
	);

	// Load Yii's generated javascript at bottom of page
	// instead of the 'head', ensuring it loads after JQuery
	Yii::app()->getClientScript()->coreScriptPosition = CClientScript::POS_END;
	?>

	<title><?php echo $this->pageTitle; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
</head>
<body>
<div id="wrapper">
	<?php $this->widget('bootstrap.widgets.BootNavbar', array(
	'collapse' => true,
	'brand'=>Yii::app()->name,
	'brandUrl'=>Yii::app()->homeUrl,
	'items'=>array(

	),
)); ?>
	<div class="container all">

		<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?>
		<?php endif?>

		<?php echo $content; ?>

		<?php
		//echo
		?>
	</div><!-- container -->
</div><!-- #wrapper -->
<footer>
	<div class="container">
		<div class="span-1">
			<p>Copyright &copy; <?php echo date('Y'); ?> by Eyetronic - All Rights Reserved.</p>
		</div>
		<div class="pull-right">
			<p style="text-align:right;">Сделано в <a href="http://art.su/" target="_blank">Eyetronic</a></p>
		</div>
	</div>
</footer>
</body>
</html>