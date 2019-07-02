<form role="form" class="form_Worktime">
	<table>
		<?php
		
		$path = Environment::addTheSlash(Environment::get('site_root')) . 'protected/views/site/';
		
		Worktime::getForm(isset($_GET['create']) ? [
			'service_id' => false,
			'reason' => false
		] : [
			'service_id' => false
		]);
		?>
		<tr>
			<td></td>
			<td>
				<button type="button" class="btn btn-primary save_changes_button save_changes_Worktime disabled"><?php
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
