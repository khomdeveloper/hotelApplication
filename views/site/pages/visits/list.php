<table class="table">
	<thead>
		<tr>
			<th><?php
				T::w([
					'Guest1' => [
						'en' => 'Guest',
						'ru' => 'Гость'
					]
				]);
				?></th>
			<th><?php
				T::w([
					'checkin' => [
						'en' => 'checkin',
						'ru' => 'зарегистрирован'
					]
				]);
				?><br/><?php
				T::w([
					'checkout' => [
						'en' => 'checkout',
						'ru' => 'выписан'
					]
				]);
				?></th>
			<th><?php
				T::w([
					'orders_th' => [
						'en' => 'orders',
						'ru' => 'заказы'
					]
				]);
				?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{{list}}
	</tbody>
</table>
<div class="ac pagination_host"></div>