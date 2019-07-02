<tr>
	<td style="width:20%;">{{label}}</td>
	<td>
		<input type="text" name="{{name}}" class="data_json form-control object_{{object}} id_{{name}}" value="{{value}}" default="{{value}}" readonly="readonly"/>
	</td>
	<td style="width:20%">
		<button type="button" class="btn btn-info select_staff"><?php
					T::w([
						'select_staff' => [
							'en' => 'Select staff',
							'ru' => 'Выбрать сотрудника'
						]
					]);
					?></button>
	</td>
</tr>