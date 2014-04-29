<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	$model->username=>array('view','id'=>$model->id),
	'Редактирование',
);
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>