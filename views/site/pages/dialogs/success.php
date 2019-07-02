<?php
if ($_GET['type'] == 'accept_offer') {
	$payment_method = UserPaymentMethod::getBy([
				'id' => $_REQUEST['user_payment']
	]);
	?>


<p class="ac" style="padding-top:10px; margin-bottom:10px;"><?php 
	T::w([
		'Press the confirmation button' => [
			'en' => 'Press the confirmation button {{button}} after payment.',
			'ru' => 'Нажмите кнопку подтверждения {{button}} после оплаты.',
			'_include' => [
				'button' => '<i class="fa fa-arrow-circle-up" style="font-size:1.8rem; vertical-align:middle; color:white;"></i>'
			]
		]
	])
?></p>
	
	
<?php } ?>
	