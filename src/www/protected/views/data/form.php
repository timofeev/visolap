<form action="<?php echo Yii::app()->createUrl('data/submit'); ?>">
	<div class="form-group has-error">
		<label>Запрос</label>
		<textarea class="form-control" name="source"></textarea>
	</div>
	<button type="submit" class="btn">Отправить</button>		
</form>