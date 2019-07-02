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
				?></th>
			<th><?php
				T::w([
					'service_name_in_guest_list' => [
						'en' => 'Service',
						'ru' => 'Сервис'
					]
				]);
				?><br/><?php
				T::w([
					'service_price_in_guest_list' => [
						'en' => 'Price',
						'ru' => 'Цена'
					]
				]);
				?></th>
			<th><?php
				T::w([
					'service_delivery_time_in_guest_list' => [
						'en' => 'Delivery time',
						'ru' => 'Когда'
					]
				]);
				?><br/><?php
				T::w([
					'service_notes_in_guest_list' => [
						'en' => 'Wishes',
						'ru' => 'Пожелания'
					]
				]);
				?></th>
			<th><button class="btn btn-xs btn-default new_order_button"><?php T::w([
				'new_order_button2' => [
					'en' => 'New',
					'ru' => 'Новый'
				]
			]) ?></button></th>
		</tr>
	</thead>
	<tbody>
		{{list}}
	</tbody>
</table>
<div class="ac pagination_host"></div>