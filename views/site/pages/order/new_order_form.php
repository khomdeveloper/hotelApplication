<?php // <form role="form" class="form_Order"> ?>
	<table class="new_order_form form_Order">
		<tr>
			<td><?php
				T::w([
					'When' => [
						'en' => 'When',
						'ru' => 'Когда'
					]
				]);
				?></td>
			<td>
				<input type="text" class="form-control object_Order id_date" value="<?php echo (new DateTime())->modify('+1 hour')->format('Y-m-d H:i'); ?>" default=""/>
			</td>
		</tr><?php
		Order::getForm([
			'visit_id'	 => false,
			'service_id' => false,
			'date'		 => false,
			'operator'	 => false,
			'hotel_id'	 => false,
			'status'	 => false
		]);
		?>
		<tr>
			<td></td>
			<td>
				<button type="button" class="btn btn-primary save_changes_Order disabled"><?php
					T::w([
						'create_save_order' => [
							'en' => 'Save order',
							'ru' => 'Сохранить заказ'
						]
					]);
					?></button>
				<span class="total_price_host_inform"><?php T::w([
					'total_price' => [
						'en' => 'Total',
						'ru' => 'Итого'
					]
					]); ?>: <span class="subtotal"></span> EUR</span>
			</td>
		</tr>
	</table>
<?php // </form> ?>