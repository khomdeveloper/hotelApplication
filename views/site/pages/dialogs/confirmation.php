<?php
if ($_GET['type'] == 'accept_balance_fillup_offer') {

	$offer = Offer::getBy([
				'id'		 => $_GET['offer_id'],
				'_notfound'	 => true
	]);

	$payment = Payment::getBy([
				'id'		 => $_GET['method'],
				'_ntofound'	 => true
	]);

	T::w([
		'accept_balance_fillup_offer_7' => [
			'en'		 => 'Do you accept the offer and ready to fill up the balance via {{method}} on {{amount}}$ {{comission}}?',
			'ru'		 => 'Вы принимаете предложение и готовы пополнить балланс через {{method}} на {{amount}}$ {{comission}}?',
			'_include'	 => [
				'amount'	 => $offer->get('amountWithComission', $_GET['requested_amount'] * 1),
				'method'	 => H::getTemplate('pages/balance/payment_method_in_title', [
					'image'	 => $payment->get(['image' => 0]),
					'href'	 => $payment->get('url'),
					'title'	 => $payment->fget('title')
						], true),
				'comission'	 => '<span class="fsz_08rem">' . $offer->getComissionString($_GET['requested_amount'] * 1) . '</span>',
			]
		]
	]);
} elseif ($_GET['type'] == 'not_enough_money_notification') {

	$offer = Offer::getBy([
				'id'		 => $_GET['offer_id'],
				'_notfound'	 => true
	]);

	if ($offer->d('amount') < $_GET['requested_amount']) {
		?><div class="rc ac yellow_message"><?php
			T::w([
				'requested_amount_not_enough1' => [
					'en'		 => 'The offer ({{offer_amount}}$) supports only a part of total requested amount ({{requested_amount}}$).',
					'ru'		 => 'Предложение ({{offer_amount}}$) обеспечивает только часть запрошенной суммы ({{requested_amount}}$).',
					'_include'	 => [
						'requested_amount'	 => $_GET['requested_amount'],
						'offer_amount'		 => $offer->get('amount')
					]
				]
			])
			?></div><?php
	}
} elseif ($_GET['type'] === 'cancel_fillup_order_red_message') {

	T::w([
		'sure_that_you_want_to_cancel_the_order_red' => [
			'en'		 => '{{div}}If You have already transferred money, they will not be returned!</div>',
			'ru'		 => '{{div}}Если Вы уже перевели средства, то они возвращены не будут!</div>',
			'_include'	 => [
				'div' => '<div class="al">'
			]
		]
	]);
} elseif ($_GET['type'] === 'cancel_withdraw_order') { //deprecated
	T::w([
		'are_you_sure_to_cancel_withdraw_order' => [
			'en' => 'Are you sure you want to cancel the withdrawal request?',
			'ru' => 'Вы уверены что хотите отменить заявку на вывод средств?'
		]
	]);
} elseif ($_GET['type'] === 'delete_confirmation_image') { //deprecated
	T::w([
		'are_you_sure_to_delete_image' => [
			'en' => 'Are you sure you want to delete image?',
			'ru' => 'Вы уверены что хотите удалить изображение?'
		]
	]);
} else {
	
}