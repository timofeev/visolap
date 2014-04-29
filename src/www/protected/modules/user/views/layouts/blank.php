<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<?php
		$themeUrl = Yii::app()->theme->baseUrl;
		$cs =  Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerCssFile($themeUrl.'/css/style.css');
	?>
	<title><?php echo $this->pageTitle; ?></title>
</head>
<body>
<?php echo $content; ?>
</body>
</html>