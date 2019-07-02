<form role="form" class="form_Permission">
	<table>
		<?php
		
		$path = Environment::addTheSlash(Environment::get('site_root')) . 'protected/views/site/';
		
		Permission::getForm([
			'object' => false,
			'object_id' => false,
			'staff_id' => $path . 'pages/service/staff_selector.php',
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
			</td>
		</tr>
	</table>	
</form>
