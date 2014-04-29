<?php $this->beginContent('//layouts/main'); ?>
<div id="sidebar" class="span2">
	<?php
	$this->widget('bootstrap.widgets.BootMenu', array(
			'type'=>'list',
			'items'=>$this->menu,
		)
	);
	?>
	
</div><!-- sidebar -->
<div class="pull-right span10">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>