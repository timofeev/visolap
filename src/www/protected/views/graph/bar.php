<form class="new-graph">
	<input type="hidden" name="type" value="bar" />
	<div class="form-group">
		<label>Ось X</label>
		<input type="text" class="form-control" name="x" />
		<select name="x_type">
			<option value="numeric">numeric</option>
			<option value="date">date</option>
			<option value="string">string</option>			
		</select>
	</div>
	<div class="form-group">
		<label>Ось Y</label>
		<input type="text" class="form-control" name="y" />		
	</div>
	<div class="form-group">
		<label>Агрегация</label>
		<select name="y_aggregation">
			<option value="sum">Sum</option>
			<option value="count">Count</option>
		</select>
	</div>
	<button class="btn btn-default" type="button">Нарисовать</button>
</form>