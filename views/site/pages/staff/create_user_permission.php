<form role="form" class="form_Permission">
	<table>
		<?php
		
		$path = Environment::addTheSlash(Environment::get('site_root')) . 'protected/views/site/';
		
		Permission::getForm([
			'staff_id' => false,
			'object' => $path . 'pages/staff/object_selector.php',
			'operation' => $path . 'pages/staff/operation_selector.php'
		]);
		?>
		<tr>
			<td></td>
			<td>
				<button type="button" class="btn btn-primary save_changes_button save_changes_Permission disabled"><?php
					T::w([
						'save_changes' => [
							'en' => 'Save changes',
							'ru' => 'Сохранить изменения'
						]
					]);
					?></button>
				<button type="button" class="btn btn-warning select_service"><?php
					T::w([
						'select_service' => [
							'en' => 'Select service',
							'ru' => 'Выбрать сервис'
						]
					]);
					?></button>
			</td>
		</tr>
	</table>	
</form>
