<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Добавление пользователя',
);
?>

<?php echo $this->renderPartial('_create', array('model'=>$model)); ?>