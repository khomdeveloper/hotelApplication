<div>
	<label class="checkbox-inline">
		<input type="radio" name="orders_type" class="orders_type type_list" value="list" checked="checked" style="width:30px;"/>
		<?php
		T::w([
			'Active orders' => [
				'en' => 'Active orders',
				'ru' => 'Активные заказы'
			]
		])
		?></label>
	<label class="checkbox-inline">
		<input type="radio" name="orders_type" class="orders_type type_history" value="history" style="width:30px;"/>
		<?php
		T::w([
			'History orders' => [
				'en' => 'History',
				'ru' => 'Архив'
			]
		])
		?></label>
	<label class="checkbox-inline">
		<input type="radio" name="orders_type" class="orders_type type_deleted" value="deleted" style="width:30px;"/>
		<?php
		T::w([
			'Deleted orders' => [
				'en' => 'Deleted',
				'ru' => 'Удаленные'
			]
		])
		?></label>
</div>