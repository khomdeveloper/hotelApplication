<table class="pin_form">
	<tr>
		<td><?php
					T::w([
						'Room' => [
							'en' => 'Room',
							'ru' => 'Номер'
						]
					]);
					?></td>
		<td>
			<input type="text" class="form-control object_Guest id_room" value="" />
		</td>
	</tr>
	<tr>
		<td><?php
					T::w([
						'PIN' => [
							'en' => 'PIN',
							'ru' => 'ПИН'
						]
					]);
					?></td>
		<td>
			<input type="text" class="form-control object_Guest id_pin" value="" />
		</td>
	</tr>
	<tr>
			<td></td>
			<td>
				<button type="button" class="btn btn-primary enter_pin_button disabled"><?php
					T::w([
						'Enter' => [
							'en' => 'Enter',
							'ru' => 'Ввод'
						]
					]);
					?></button>
			</td>
		</tr>
</table>