<table>
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
			<div class="input-group date" id="datetimepicker3">
					<input type="text" class="form-control extend_till id_end" value="<?php echo (new DateTime())->modify('+7 days')->format('Y-m-d H:i:s'); ?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
		</td>
		<td>
			<button type="button" class="btn btn-primary extend_Visit disabled"><?php
					T::w([
						'Extend (time)' => [
							'en' => 'Extend',
							'ru' => 'Продлить'
						]
					]);
					?></button>
		</td>
	</tr>
</table>
