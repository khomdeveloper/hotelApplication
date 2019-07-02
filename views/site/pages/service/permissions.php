<table class="table">
	<thead>
		<tr>
			<th><?php
				T::w([
					'user_name(in role group)' => [
						'en' => 'Staff name',
						'ru' => 'Персонал'
					]
				]);
				?></th>
			<th><?php
				T::w([
					'operation(in role group)' => [
						'en' => 'Operation',
						'ru' => 'Операция'
					]
				]);
				?></th>
			<th>
				<button type="button" class="btn btn-success btn-xs create_new_Permission" style="float:right;"><?php
					T::w([
						'new_permission' => [
							'en' => 'New permission',
							'ru' => 'Новое разрешение'
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