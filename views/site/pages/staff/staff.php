<?php
$user = User::logged();
?>
<div class="page_content">
	<div class="page_block al">
		<div style="padding:10px;">
			<div class="al">	
				<div class="admin_data">
					<h1><?php
						T::w([
							'admin_profile_h2' => [
								'en' => 'Staff control',
								'ru' => 'Управление персоналом'
							]
						])
						?></h1>
					<?php
					echo H::getTemplate('pages/user/find_form', [
						'checkboxes' => H::getTemplate('pages/staff/checkboxes',[],true)
					],true);
					?>
					
					<?php
							T::w([
								'select_user_to_show_his_permissions' => [
									'en' => '⬆ Select the user to view and edit his permissions',
									'ru' => '⬆ Выберите пользователя чтобы управлять его разрешениями'
								]
							]);
						?>
				</div>	

				<div class="staff_permissions" style="display:none">

					<h2 class="staff_permissions_h2"><?php
						T::w([
							'staff_permissions_h2' => [
								'en' => 'Staff permissions',
								'ru' => 'Роли персонала'
							]
						])
						?></h2>

					<div class="permissions_list"></div>

				</div>

				<div class="role_categories" style="display:none;">

					<h2 class="role_categories_h2"><?php
						T::w([
							'role_categories_h2' => [
								'en' => 'Service role categories',
								'ru' => 'Категории ролей персонала'
							]
						])
						?></h2>

					<div class="categories_list">

					</div>

				</div>

			</div>
		</div>
	</div>
</div>