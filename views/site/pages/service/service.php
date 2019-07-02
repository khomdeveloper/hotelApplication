<?php
$user = User::logged();
?>
<div class="page_content">
	<div class="page_block al service_page">
		<div style="padding:10px;">
			<div class="al">

				<div class="service_list">
					<h1 class="service_list_h2"><?php
						T::w([
							'service_list_h2' => [
								'en' => 'Service List',
								'ru' => 'Список сервисов'
							]
						]);
						?></h1>

					<div class="service_tree">

					</div>

					<form role="form">
						<button type="button" class="btn btn-primary btn-xs create_new_Service" type="button"><?php
							T::w([
								'add_new_service' => [
									'en' => 'Add new',
									'ru' => 'Добавить'
								]
							]);
							?></button>
					</form>

				</div>

				<div class="service_data" style="display:none;">
					<h2 class="service_profile_h2"><?php
						T::w([
							'service_profile_h2' => [
								'en' => 'Service Profile',
								'ru' => 'Описание сервиса'
							]
						]);
						?></h2>

					<form role="form" class="form_Service">
						<table>
							<?php
							$path = Environment::addTheSlash(Environment::get('vh2015')) . 'templates/form/';

							Service::getForm([
								'title'		 => $path . 'json_input.php',
								'country'	 => $path . 'country.php',
								'parent_id'	 => false,
								'hotel_id'	 => false,
								'visibility' => false,
								'type' => false
							]);
							?>
							<tr>
								<td></td>
								<td>
									<label class="checkbox-inline"><input type="checkbox" value="" class="service_visibility" checked="checked"><?php
										T::w([
											'service_hidden_cehckbox' => [
												'en' => 'Hidden',
												'ru' => 'Скрыт'
											]
										]);
										?> </label>
									
									<label class="checkbox-inline service_list_host3245"><input type="checkbox" value="" class="service_list_3245"><?php
										T::w([
											'service_aslist_cehckbox' => [
												'en' => 'Show as selection list',
												'ru' => 'Показывать как список выбора'
											]
										]);
										?> </label>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button type="button" class="btn btn-primary save_changes_Service disabled"><?php
										T::w([
											'save_changes' => [
												'en' => 'Save changes',
												'ru' => 'Сохранить изменения'
											]
										]);
										?></button>
									<button type="button" class="btn btn-danger remove_Service"><?php
										T::w([
											'remove' => [
												'en' => 'Remove',
												'ru' => 'Удалить'
											]
										]);
										?></button>
								</td>
							</tr>
							<tr>
								<td>
									<div class="upload_new"></div>
								</td>
								<td>
									<div class="Service_logo_host"></div>
								</td>
							</tr>
						</table>
					</form>

					<div class="worktime" style="margin-top:20px;">

						<h2 class="worktime_h2"><?php
							T::w([
								'worktime_h2' => [
									'en' => 'Working time',
									'ru' => 'Часы работы'
								]
							])
							?></h2>

						<div class="worktime_list">

						</div>

					</div>
					
					
					<div class="staff_permissions" style="margin-top:20px;">

						<h2 class="staff_permissions_h2"><?php
							T::w([
								'staff_permissions_h2' => [
									'en' => 'Staff permissions',
									'ru' => 'Доступ персонала'
								]
							])
							?></h2>

						<div class="permissions_list">

						</div>

					</div>

				</div>




			</div>
		</div>
	</div>
</div>	