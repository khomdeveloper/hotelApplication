<form role="form" class="form_Permission">
	<table>
		<tr>
			<td><?php
				T::w([
					'checkin from' => [
						'en' => 'from',
						'ru' => 'с'
					]
				]);
				?></td>
			<td><?php
				T::w([
					'checkin till' => [
						'en' => 'till',
						'ru' => 'до'
					]
				]);
				?></td>
		</tr>
		<tr>
			<td>
				<div class="input-group date" id="datetimepicker1">
					<input type="text" class="form-control object_Guest id_begin" value="<?php echo (new DateTime())->format('Y-m-d') . ' 12:00:00'; ?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</td>
			<td>
				<div class="input-group date" id="datetimepicker2">
					<input type="text" class="form-control object_Guest id_end" value="<?php echo (new DateTime())->modify('+7 days')->format('Y-m-d') . ' 12:00:00'; ?>"/>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
				</div>
			</td>
		</tr>
		<tr>
			<td class="ar"><?php
				T::w([
					'Room no' => [
						'en' => 'Room',
						'ru' => 'Номер'
					]
				])
				?></td>
			<td>
				<input type="text" class="form-control object_Guest id_room" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<button type="button" class="btn btn-primary create_Visit disabled"><?php
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