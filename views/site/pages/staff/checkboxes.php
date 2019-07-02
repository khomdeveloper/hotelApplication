<tr>
	<td></td>
	<td>
		<label class="checkbox-inline">
			<input type="radio" name="user_type" class="user_type_radio user_type_staff" value="staff" checked="checked" style="width:30px;"/>
			<?php
			T::w([
				'Only staff' => [
					'en' => 'Only staff',
					'ru' => 'Только персонал'
				]
			])
			?></label>
		<?php /* ?>
		<label class="checkbox-inline">
			<input type="radio" name="user_type" class="user_type_radio user_type_visitor" value="visitor" style="width:30px;"/>
			<?php
			T::w([
				'not_registered_visitors' => [
					'en' => 'Not registered visitors',
					'ru' => 'Незарегистрированные посетители'
				]
			])
			?></label><?php */ ?>
	</td>
	<td></td>
</tr>