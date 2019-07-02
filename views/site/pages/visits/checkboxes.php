<tr>
	<td></td>
	<td>
		<label class="checkbox-inline">
			<input type="radio" name="guest_type" class="guest_type_radio guest_type_guest" value="guest" checked="checked" style="width:30px;"/>
			<?php
			T::w([
				'Active visits' => [
					'en' => 'Active visits',
					'ru' => 'Активные визиты'
				]
			])
			?></label>
		<label class="checkbox-inline">
			<input type="radio" name="guest_type" class="guest_type_radio guest_type_past" value="past" style="width:30px;"/>
			<?php
			T::w([
				'Past visits' => [
					'en' => 'Past visits',
					'ru' => 'Прошлые визиты'
				]
			])
			?></label>
		<?php /* ?>
		<label class="checkbox-inline">
			<input type="radio" name="guest_type" class="guest_type_radio guest_type_visitor" value="visitor" style="width:30px;"/>
			<?php
			T::w([
				'not_registered_visitors' => [
					'en' => 'Not registered visitors',
					'ru' => 'Незарегистрированные посетители'
				]
			])
			?></label>
		 <?php */ ?> 
	</td>
	<td></td>
</tr>