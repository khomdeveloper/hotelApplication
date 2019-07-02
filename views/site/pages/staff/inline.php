<tr class="object_User_tr {{staff}}">
	<td class="object_User id_{{user_id}} {{staff}}">{{name}}</td>
	<td class="object_User id_{{user_id}} {{staff}}">{{email}}</td>
	<td>
		<form role="form" >
		<select name="role" class="form-control user_role id_{{user_id}}">
			<?php /* ?>
			<option value="guest"><?php 
				T::w([
					'guest' => [
						'en' => 'guest',
						'ru' => 'гость'
					]
				])
			?></option><?php */ ?>
			<option value="staff"><?php 
				T::w([
					'staff' => [
						'en' => 'staff',
						'ru' => 'персонал'
					]
				])
			?></option>
			<option value="admin"><?php 
				T::w([
					'admin_role(in selector)' => [
						'en' => 'admin',
						'ru' => 'админ'
					]
				])
			?></option>
			<option value="visitor"><?php 
				T::w([
					'not_linked_role' => [
						'en' => 'not linked',
						'ru' => 'не связан'
					]
				]);
			?></option>
		</select>
		</form>
	</td>
</tr>