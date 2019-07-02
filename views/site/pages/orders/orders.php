<?php
$user = User::logged();
$hotel = Hotel::getCurrent();
?>
<div class="page_content">
	<div class="page_block al orders_page">
		<div style="padding:10px;">
			<h1 class="orders_page_h2"><?php
				T::w([
					'orders_page_h2' => [
						'en' => 'Orders',
						'ru' => 'Заказы'
					]
				]);
				?></h1>
			<?php
			if (in_array($user->get('role'), ['staff',
						'admin'])) {
				echo H::getTemplate('pages/orders/checkboxes', [], true);
			}
			?>
			<div class="al orders_list_host"></div>
		</div>
	</div>
</div>