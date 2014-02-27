<div class="alerts"></div>
<div class="windows">
	<div class="window" id="source">
		<form action="<?php echo Yii::app()->createUrl('site/submit'); ?>">
			<div class="form-group has-error">
				<label>Запрос</label>
				<textarea class="form-control" name="source"></textarea>
			</div>
			<button type="submit" class="btn">Отправить</button>		
		</form>
	</div>
</div>