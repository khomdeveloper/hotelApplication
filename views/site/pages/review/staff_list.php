<table class="table">
	<thead>
		<tr>
			<th class="field_name"><?php
				T::w([
					'user_name' => [
						'en' => 'Name',
						'ru' => 'Имя'
					]
				]);
				?><br/><?php
				T::w([
					'user_room' => [
						'en' => 'room',
						'ru' => 'номер'
					]
				]);
				?><br/><?php
			T::w([
				'Date' => [
					'en' => 'date',
					'ru' => 'Дата'
				]
			]);
				?></th>
			<th><?php
				T::w([
						'Rate' => [
							'en' => 'Rate',
							'ru' => 'Рейтинг'
						]
					]);
			?></th>
			<th><?php 
				T::w([
					'user_message' => [
						'en' => 'Message',
						'ru' => 'Сообщение'
					]
				])
			?></th>
		</tr>
	</thead>
	<tbody>
		{{list}}
	</tbody>
</table>
<div class="ac pagination_host"></div>