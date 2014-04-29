<?php $this->beginContent('//layouts/main'); ?>
<?php
	if ($this->menu) {
		$this->widget('bootstrap.widgets.TbButtonGroup', array(
				'type'=>'list',
				'buttons'=>$this->menu,
			)
		);
	}
?>

<?php echo $content; ?>

<?php $this->endContent(); ?>