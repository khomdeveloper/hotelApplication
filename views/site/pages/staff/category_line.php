<tr>
	<td>{{group}}</td>
	<td>{{services}}</td>
	<td>{{staff}}</td>
	<td>
		<button type="button" class="btn btn-danger btn-xs remove_PermissionGroup id_{{group_name}}"><?php
			T::w([
				'remove' => [
					'en' => 'Remove',
					'ru' => 'Удалить'
				]
			]);
			?></button>
		<button type="button" class="btn btn-primary btn-xs edit_PermissionGroup id_{{group_name}}"><?php
			T::w([
				'edit' => [
					'en' => 'Edit name',
					'ru' => 'Редактировать'
				]
			]);
			?></button>
	</td>
</tr>