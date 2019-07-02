<table class="table">
	<thead>
		<tr>
			<th><?php
				T::w([
					'category_name' => [
						'en' => 'Category name',
						'ru' => 'Имя категории'
					]
				]);
				?></th>
			<th><?php
				T::w([
					'category_name (in list)' => [
						'en' => 'Category name',
						'ru' => 'Имя категории'
					]
				]);
				?></th><th><?php
				T::w([
					'staff (in list)' => [
						'en' => 'Staff',
						'ru' => 'Персонал'
					]
				]);
				?></th><th><?php
					T::w([
						'category_name (in list)' => [
							'en' => 'Category name',
							'ru' => 'Имя категории'
						]
					]);
					?></th><th><?php
					T::w([
						'servcies (in list)' => [
							'en' => 'Services',
							'ru' => 'Сервисы'
						]
					]);
					?></th>
			<th>
				<button type="button" class="btn btn-xs add_PermissionGroup"><?php
					T::w([
						'new_category (in list)' => [
							'en' => 'New category',
							'ru' => 'Новая категория'
						]
					]);
					?></button>
				?></th>
		</tr>
	</thead>
	<tbody></tbody>
</table>