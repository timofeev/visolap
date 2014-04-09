<div class="window<?php if ($class) { echo " ".$class; } ?>" id="<?php echo $id; ?>" <?php echo isset($window['parent_id']) ? 'data-parent_id="'.$window['parent_id'].'"' : ''; ?>>
	<div class="resize">
		<div class="content" id="<?php echo $id; ?>_content">
			<?php echo $content; ?>
		</div>
	</div>
	<div class="source-data"></div>
</div>