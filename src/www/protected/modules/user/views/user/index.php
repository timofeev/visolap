<?php
$this->breadcrumbs=array(
	'Пользователи',
);
$this->menu=array(
	array('label'=>'Добавить пользователя', 'url'=>array('create'), 'htmlOptions' => array('class' => 'btn-info')),
);

$this->widget('bootstrap.widgets.TbGridView', array(
	'dataProvider'=>$model->search(),
	'type' => 'striped bordered condensed',
	'template' => "{items} \n {pager}",
	'filter' => $model,
	'rowCssClassExpression' => 'checkStatus($data->status)',
	'columns'=>array(
 			array(
 				'name' => 'id',
 				'value' => '$data->id',
 				'htmlOptions'=>array('style'=>'width: 28px'),
 			),
			'username',
			'email',
			array(
				'name'=>'created',
				'value'=>'format_date($data->created)',
			),
			array(
				'name'=>'last_login',
				'value'=>'format_date($data->last_login)',
			),
			array(
				'name'=>'status',
				'value'=>'get_status($data->status)',
			),
			array(
         	   'class'=>'bootstrap.widgets.TbButtonColumn',
         	   'template' => '{status} {view} {update} {delete}',
         	   'buttons' => array(
         	   		'status'=>array(
       	   				'icon' => 'check',
       	   				'label' => 'Изменить статус',
       	   				'url' => 'Yii::app()->createUrl("/user/user/changestatus", array("id" => $data->id))',
         	   		),
         	   ),
               'htmlOptions'=>array('style'=>'width: 68px'),
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
		return "Активен";
	return "Не активен";
}
function checkStatus($status){
	if (!$status)
		return "blocked";
	return false;
}
?>