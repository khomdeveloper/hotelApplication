<table style="width:100%;" class="generate_pin_host">
	<tr>
		<td><?php 
			T::w([
					'room' => [
						'en' => 'room',
						'ru' => 'номер'
					]
				]);
		?></td>
		<td>
			<input type="text" class="form-control room_number" value="" />
		</td>
	</tr>
	<tr>
		<td><?php 
			T::w([
					'checkin from' => [
						'en' => 'from',
						'ru' => 'с'
					]
				]);
		?></td>
		<td>
			<div class="input-group date" id="datetimepicker6">
					<input type="text" class="form-control pin_start" value="<?php echo (new DateTime())->format('Y-m-d H:i:s'); ?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
		</td>
	</tr>
	<tr>
		<td><?php 
			T::w([
					'checkin till' => [
						'en' => 'till',
						'ru' => 'до'
					]
				]);
		?></td>
		<td>
			<div class="input-group date" id="datetimepicker7">
					<input type="text" class="form-control pin_expired" value="<?php echo (new DateTime())->modify('+7 days')->format('Y-m-d H:i:s'); ?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td class="ar">
			<button type="button" class="btn btn-primary generate_it disabled"><?php
					T::w([
						'generate_new_pincode' => [
							'en' => 'Create PIN',
							'ru' => 'Создать PIN'
						]
					]);
					?></button>
		</td>
	</tr>
</table>