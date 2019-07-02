<table class="table">
	<thead>
		<tr>
			<?php /* <th><?php
				T::w([
					'role_group' => [
						'en' => 'Group',
						'ru' => 'Группа'
					]
				]);
				?></th> */ ?>
			<th><?php
				T::w([
					'object_name(in role group)' => [
						'en' => 'Object type',
						'ru' => 'Тип объекта'
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