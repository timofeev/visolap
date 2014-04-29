<?php
	$editable = !Yii::app()->user->isGuest;	
	if ($editable && isset($page)) {
		$editable = Yii::app()->user->checkAccess('admin') || (Yii::app()->user->id == $page->user_id);
	}
?>
<?php if (!$editable) { ?>
<style type="text/css">
	.window a.edit {
		display: none;
	}
</style>
<?php } ?>
<div id="loading"></div>
<?php $this->widget('bootstrap.widgets.TbNavbar',array(
	'items'=>array(
	    array(
	        'class'=>'bootstrap.widgets.TbMenu',
	        'items'=>array(
	            array('label'=>'Главная', 'url'=>'/'),
	            array('label'=>'Авторизация', 'url'=>array('/auth'), 'visible'=>Yii::app()->user->isGuest),
	            array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/logout'), 'visible'=>!Yii::app()->user->isGuest,),
	            array(
	                'label' => 'Добавить данные',
	                'url' => 'javascript:void(0);',
	                'itemOptions' => array('id' => 'addDataButton', 'data-url' => Yii::app()->createUrl('data/form')),
	                'visible'=>$editable
	            ),
	            array(
	                'label' => 'Сохранить',
	                'url' => 'javascript:void(0);',
	                'itemOptions' => array('id' => 'saveLayoutButton', 'data-url' => Yii::app()->createUrl('wiki/layout/save'), 'data-name' => isset($page) ? $page->getWikiUid() : uniqid()),
	                'visible'=>$editable
	            ),
	        ),
	    ),
	),
)); ?>
<div class="container" id="page">
	<div class="alerts"></div>
	<div class="windows">
		<?php
			if (isset($page)) {
				foreach($content as $window) {
					$this->renderWindowContent($window, $window['id']);
				}
			}
		?>
	</div>
</div><!-- page -->