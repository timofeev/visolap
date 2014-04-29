<?php $this->beginContent(Rights::module()->appLayout); ?>

<div id="rights" class="container">

	<div id="content">

		<?php if( $this->id!=='install' ): ?>

			<div id="menu">

				<?php $this->renderPartial('/_menu'); ?>

			</div>

		<?php endif; ?>

		<?php echo $content; ?>
		
	</div><!-- content -->
	<?php $this->renderPartial('/_flash'); ?>

</div>

<?php $this->endContent(); ?>