<table class="table">
	<thead>
		<tr>
			<th><?php
				T::w([
					'weeday(in worktime)' => [
						'en' => 'Day of week',
						'ru' => 'День недели'
					]
				]);
				?></th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<button type="button" class="btn btn-success btn-xs create_new_Worktime" style="float:right;"><?php
					T::w([
						'create_new_interval' => [
							'en' => 'New interval',
							'ru' => 'Новый интервал'
						]
					]);
					?></button>
			</th>
		</tr>
	</thead>
	<tbody>
		{{list}}
	</tbody>
</table>