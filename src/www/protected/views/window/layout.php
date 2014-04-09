<div class="window<?php if ($class) { echo " ".$class; } ?>" id="<?php echo $id; ?>" <?php echo isset($window['parent_id']) ? 'data-parent_id="'.$window['parent_id'].'"' : ''; ?>>
	<div class="resize" style="<?php if (isset($window['width'])) { echo 'width:'.$window['width'].'px; '; } if (isset($window['height'])) { echo 'height:'.$window['height'].'px;'; } ?>">
		<div class="content" id="<?php echo $id; ?>_content">
			<?php echo $content; ?>
		</div>
	</div>
	<div class="source-data"></div>
</div>