<div class="new-graph">
	<input type="hidden" name="type" value="linear" />
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
		<select name="y_type">
			<option value="numeric">numeric</option>
			<option value="date">date</option>
			<option value="string">string</option>			
		</select>
	</div>
	<div class="form-group">
		<label>Агрегация</label>
		<select name="aggregation">
			<option value="sum">Sum</option>
			<option value="count">Count</option>
		</select>
	</div>
	<button class="btn btn-default">Нарисовать</button>
</div>