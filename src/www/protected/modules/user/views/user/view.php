<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	$model->username,
);

$this->menu=array(	
	array('label'=>'Редактировать пользователя', 'url'=>array('update?id='.$model->id), 'htmlOptions' => array('class' => 'btn-info')),
	array('label'=>'Удалить пользователя', 'url'=>array('delete?id='.$model->id), 'htmlOptions' => array('class' => 'btn-danger', 'submit' => array('delete', 'id' => $model->id), 'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'))),
);
?>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'email',
		array(
			'name'=>'created',
			'value'=>format_date($model->created),
		),
		array(
			'name'=>'last_login',
			'value'=>format_date($model->last_login),
		),
		array(
			'name'=>'updated',
			'value'=>format_date($model->updated),
		),
		array(
			'name'=>'status',
			'value'=>get_status($model->status),
			'type'=>'html',
		),
	)
));

 function format_date($date){
	$t = CDateTimeParser::parse($date,'yyyy-MM-dd HH:mm:ss');
	if ($t)
		return date ("Y.m.d H:i", $t);

	return "-";		
}

function get_status($status){
	if ($status)
		return "<span class='green'>Активен</span>";
	return "<span class='red'>Не активен</span>";
}

?>
