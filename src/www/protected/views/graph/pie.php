<form class="new-graph">
	<input type="hidden" name="type" value="pie" />
	<div class="form-group">
		<label>Поле</label>
		<input type="text" class="form-control" name="x" />
		<select name="x_type">
			<option value="numeric">numeric</option>
			<option value="date">date</option>
			<option value="string">string</option>			
		</select>		
	</div>	
	<button class="btn btn-default" type="button">Нарисовать</button>
</form>