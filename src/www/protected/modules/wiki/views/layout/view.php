<div id="loading"></div>
<?php $this->widget('bootstrap.widgets.TbNavbar',array(
	'items'=>array(
	    array(
	        'class'=>'bootstrap.widgets.TbMenu',
	        'items'=>array(
	            array('label'=>'Главная', 'url'=>'/'),
	            array(
	                'label' => 'Добавить данные',
	                'url' => 'javascript:void(0);',
	                'itemOptions' => array('id' => 'addDataButton', 'data-url' => Yii::app()->createUrl('data/form'))	                	
	            ),
	            array(
	                'label' => 'Сохранить',
	                'url' => 'javascript:void(0);',
	                'itemOptions' => array('id' => 'saveLayoutButton', 'data-url' => Yii::app()->createUrl('wiki/layout/save'), 'data-name' => isset($page) ? $page->getWikiUid() : uniqid())	                	
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