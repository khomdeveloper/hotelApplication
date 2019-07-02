<?php
$user = User::logged();

$countries = Countries::getBy([
			'_all' => ['code' => 'object'],
			'_order' => '`title_' . T::getLocale() . '`'
		]);
?>
<div class="page_content">
	<div class="page_block al user_profile_page">
		<div style="padding:10px;">
			<div class="al">

				<div class="user_data">
					<div class="menu_item goto_help_page" style="float:right; font-size:0.9rem;"><i class="fa fa-info" style="font-size:18px; margin-right:5px;"></i><?php
						T::w([
							'About the service' => [
								'en' => 'About the service',
								'ru' => 'О нашем сервисе'
							]
						]);
						?></div>
					<h1 class="user_profile_h2"><?php
						T::w([
							'user_profile' => [
								'en' => 'User Profile',
								'ru' => 'Профиль пользователя'
							]
						]);
						?></h1>
					<form role="form">
						<table>
							<tr class="user_email_host" style="display:none;">
								<td style="width:20%;"><?php
									T::w([
										'email' => [
											'en' => 'Email',
											'ru' => 'Email'
										]
									]);
									?></td>
								<td>
									<input type="text" name="email" class="form-control id_email" value="" placeholder="<?php
									T::w([
										'email' => [
											'en' => 'Email',
											'ru' => 'Email'
										]
									]);
									?>" default=""/></td>
							</tr>
							<tr>
								<td style="width:20%;"><?php
									T::w([
										'First name' => [
											'en' => 'First name',
											'ru' => 'Имя'
										]
									]);
									?></td>
								<td>
									<input type="text" name="first_name" class="form-control id_first_name" value="" placeholder="<?php
									T::w([
										'First name' => [
											'en' => 'First name',
											'ru' => 'Имя'
										]
									]);
									?>" default=""/></td>
							</tr>
							<tr>
								<td><?php
									T::w([
										'Last name' => [
											'en' => 'Last name',
											'ru' => 'Фамилия'
										]
									]);
									?></td>
								<td>
									<input type="text" name="last_name" class="form-control id_last_name" value="" placeholder="<?php
									T::w([
										'Last name' => [
											'en' => 'Last name',
											'ru' => 'Фамилия'
										]
									]);
									?>" default=""/></td>
							</tr>
							<tr>
								<td><?php
									T::w([
										'Phone number' => [
											'en' => 'Phone number',
											'ru' => 'Номер телефона'
										]
									]);
									?></td>
								<td>
									<input type="text" name="phone" class="form-control id_phone" value="" placeholder="<?php
									T::w([
										'Phone number' => [
											'en' => 'Phone number',
											'ru' => 'Номер телефона'
										]
									]);
									?>" default=""/></td>
							</tr>
							<tr>
								<td><?php
									T::w([
										'Birthday' => [
											'en' => 'Birthday',
											'ru' => 'День рождения'
										]
									]);
									?></td>
								<td style="text-shadow:none;">
									<input type="text" name="birthday" class="form-control id_birthday" value="" placeholder="<?php
									T::w([
										'Birthday' => [
											'en' => 'Birthday',
											'ru' => 'День рождения'
										]
									]);
									?>" default=""/></td>
							</tr>
							<tr>
								<td><?php
									T::w([
										'Gender' => [
											'en' => 'Gender',
											'ru' => 'Пол'
										]
									]);
									?></td>
								<td>
									<select name="gender" class="form-control id_gender">
										<option class="option_default" value selected><?php
											T::w([
												'not_specified3' => [
													'en' => 'not specified',
													'ru' => 'не указано'
												]
											]);
											?></option>
										<option class="option_male" value="male"><?php
											T::w([
												'male' => [
													'en' => 'male',
													'ru' => 'мужчина'
												]
											]);
											?></option>
										<option class="option_female" value="female"><?php
											T::w([
												'female' => [
													'en' => 'female',
													'ru' => 'женщина'
												]
											]);
											?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td><?php
									T::w([
										'Country' => [
											'en' => 'Country',
											'ru' => 'Страна'
										]
									]);
									?></td>
								<td>
									<select name="country" class="form-control id_country">
										<option value selected><?php
											T::w([
												'not_specified3' => [
													'en' => 'not specified',
													'ru' => 'не указано'
												]
											]);
											?></option>
										<?php
										if (!empty($countries)) {
											foreach ($countries as $record) {
												?>
												<option class="option_<?php $record->fout('code'); ?>" value="<?php $record->fout('code'); ?>"><?php $record->fout('title_' . strtolower(T::getLocale())); ?></option>
												<?php
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div class="upload_new" style="display:inline-block;">
										<label class="cp">
											<div class="cp pa User_uploader_host_<?php echo $user->get('id'); ?>_new" style="width:auto; height:auto; opacity:0; left:-10000px;"></div>
											<span class="change_photo_host"><i class="fa fa-upload" aria-hidden="true" style="margin-right:10px;"></i><?php
												T::w([
													'change_avatar' => [
														'en' => 'Change photo',
														'ru' => 'Изменить фото'
													]
												]);
												?></span> 
										</label>
									</div>
									<button style="margin-left:10px;" type="button" class="remove_photo id_<?php echo $user->get('id'); ?> btn btn-xs btn-danger"><?php
										T::w([
											'remove_photo' => [
												'en' => 'Remove photo',
												'ru' => 'Удалить фотографию'
											]
										]);
										?></button>
								</td>
							</tr>
							<tr>
								<td>

								</td>
								<td>
									<button type="button" class="btn btn-primary save_profile_changes"><?php
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
				</div>
				<!-- Change login/pass -->
				<div class="user_login_pass" style="display:none;">
					<h2><?php
						T::w([
							'change_login_pass' => [
								'en' => 'Change login and pass',
								'ru' => 'Изменить логин и пароль'
							]
						]);
						?></h2>
					<form role="form">
						<table>
							<tr>
								<td style="width:20%;"><?php
									T::w([
										'e-mail_in8' => [
											'en' => 'Email',
											'ru' => 'Email'
										]
									]);
									?></td><td>
									<input type="text" name="login" class="form-control id_login" value="" placeholder="<?php
									T::w([
										'e-mail_in8' => [
											'en' => 'Email',
											'ru' => 'Email'
										]
									]);
									?>" default=""/></td>
								<td>
									<button type="button" class="btn btn-primary change_login disabled"><?php
										T::w([
											'change_login' => [
												'en' => 'Change login',
												'ru' => 'Изменить логин'
											]
										]);
										?></button>
								</td>
							</tr>
							<tr>
								<td style="width:20%;"><?php
									T::w([
										'new_pass_in8' => [
											'en' => 'New pass',
											'ru' => 'Новый пароль'
										]
									]);
									?></td><td>
									<input type="password" name="new_pass" class="form-control id_new_pass" value="" placeholder="<?php
									T::w([
										'new_pass_in8' => [
											'en' => 'New pass',
											'ru' => 'Новый пароль'
										]
									]);
									?>" default=""/></td>
								<td></td>
							</tr>
							<tr>
								<td style="width:20%;"><?php
									T::w([
										'repeat_pass_in8' => [
											'en' => 'Repeat pass',
											'ru' => 'Повторить пароль'
										]
									]);
									?></td>
								<td>
									<input type="password" name="repeat_pass" class="form-control id_repeat_pass" value="" placeholder="<?php
									T::w([
										'repeat_pass_in8' => [
											'en' => 'Repeat pass',
											'ru' => 'Повторить пароль'
										]
									]);
									?>" default=""/></td>
								<td>
									<button type="button" class="btn btn-primary change_pass disabled"><?php
										T::w([
											'change_pass' => [
												'en' => 'Change pass',
												'ru' => 'Изменить пароль'
											]
										]);
										?></button>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>